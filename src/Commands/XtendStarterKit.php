<?php

namespace XtendPackages\RESTPresenter\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use SplFileInfo;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'rest-presenter:xtend-starter-kit')]
class XtendStarterKit extends Command
{
    protected $signature = 'rest-presenter:xtend-starter-kit {name}';

    protected $description = 'Xtend REST Presenter Starter Kit';

    public function __construct(protected Filesystem $filesystem)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $starterKitsDirectory = __DIR__ . '/../StarterKits';
        $generatedKitsDirectory = config('rest-presenter.generator.path') . '/StarterKits';
        $kitPath = collect($this->filesystem->allFiles($starterKitsDirectory))
            ->filter(fn ($file) => Str::contains($file->getFilename(), $this->argument('name')))
            ->first()
            ->getRelativePath();

        if ($this->kitAlreadyExtended($generatedKitsDirectory, $kitPath)) {
            $this->components->warn(__('The starter kit ":kit" has already been extended!', ['kit' => $this->argument('name')]));

            return self::FAILURE;
        }

        $this->generateStarterKit($starterKitsDirectory, $generatedKitsDirectory, $kitPath);

        return self::SUCCESS;
    }

    protected function generateStarterKit(string $starterKitsDirectory, string $generatedKitsDirectory, string $kitPath): void
    {
        $this->extendResources($starterKitsDirectory, $kitPath);
    }

    protected function extendResources(string $starterKitsDirectory, string $kitPath): void
    {
        if ($this->kitHasResources($starterKitsDirectory, $kitPath)) {
            $resources = collect($this->filesystem->allFiles($starterKitsDirectory . '/' . $kitPath . '/Resources'))
                ->filter(fn (SplFileInfo $file) => Str::endsWith($file->getFilename(), 'ResourceController.php'))
                ->map(fn (SplFileInfo $file) => $file->getRelativePath())
                ->each(function ($path) use ($kitPath) {
                    $kitNamespace = Str::of($kitPath)
                        ->replace('/', '\\')->prepend('StarterKits\\')
                        ->append('\\Resources\\' . Str::of($path)->replace('/', '\\')->beforeLast('\\'))
                        ->value();

                    $resource = basename($path);

                    return $this->call('rest-presenter:make-resource', [
                        'kit_namespace' => $kitNamespace . '\\' . $resource,
                        'name' => Str::singular(basename($path)),
                    ]);
                });
        }
    }

    protected function kitHasResources(string $starterKitsDirectory, string $kitPath): bool
    {
        return $this->filesystem->exists($starterKitsDirectory . '/' . $kitPath . '/Resources');
    }

    protected function kitAlreadyExtended(string $generatedKitsDirectory, string $kitPath): bool
    {
        return $this->filesystem->exists($generatedKitsDirectory . '/' . $kitPath);
    }
}
