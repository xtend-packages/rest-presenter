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
        $this->extendControllers($starterKitsDirectory, $kitPath);
        $this->extendResources($starterKitsDirectory, $kitPath);
    }

    protected function extendControllers(string $starterKitsDirectory, string $kitPath): void
    {
        if ($this->kitHasControllers($starterKitsDirectory, $kitPath)) {
            collect($this->filesystem->allFiles($starterKitsDirectory . '/' . $kitPath . '/Http/Controllers'))
                ->filter(fn (SplFileInfo $file) => Str::endsWith($file->getFilename(), 'Controller.php'))
                ->map(fn (SplFileInfo $file) => $file->getRelativePathName())
                ->each(function ($path) use ($kitPath) {
                    $kitNamespace = Str::of($kitPath)
                        ->replace('/', '\\')->prepend('StarterKits\\')
                        ->append('\\Http\\Controllers')
                        ->value();

                    $controller = basename($path);
                    $this->call('rest-presenter:make-controller', [
                        'kit_namespace' => $kitNamespace,
                        'name' => Str::beforeLast($controller, '.php'),
                        'type' => 'extend',
                    ]);
                });
        }
    }

    protected function extendResources(string $starterKitsDirectory, string $kitPath): void
    {
        if ($this->kitHasResources($starterKitsDirectory, $kitPath)) {
            collect($this->filesystem->allFiles($starterKitsDirectory . '/' . $kitPath . '/Resources'))
                ->filter(fn (SplFileInfo $file) => Str::endsWith($file->getFilename(), 'ResourceController.php'))
                ->map(fn (SplFileInfo $file) => $file->getRelativePath())
                ->each(function ($path) use ($starterKitsDirectory, $kitPath) {
                    $kitNamespace = Str::of($kitPath)
                        ->replace('/', '\\')->prepend('StarterKits\\')
                        ->append('\\Resources\\' . Str::of($path)->replace('/', '\\')->beforeLast('\\'))
                        ->value();

                    $resource = basename($path);
                    $resourcePath = $kitPath . '/Resources/' . $path;
                    $kitNamespace = $kitNamespace . '\\' . $resource;
                    $this->extendPresenters($starterKitsDirectory, $resourcePath, $kitNamespace);

                    return $this->call('rest-presenter:make-resource', [
                        'kit_namespace' => $kitNamespace,
                        'name' => Str::singular(basename($path)),
                        'type' => 'extend',
                    ]);
                });
        }
    }

    protected function extendPresenters(string $starterKitsDirectory, string $resourcePath, string $kitNamespace): void
    {
        if (! $this->filesystem->exists($starterKitsDirectory . '/' . $resourcePath . '/Presenters')) {
            return;
        }

        collect($this->filesystem->directories($starterKitsDirectory . '/' . $resourcePath . '/Presenters'))
            ->each(function ($path) use ($kitNamespace) {
                $presenter = basename($path);

                $this->call('rest-presenter:make-presenter', [
                    'kit_namespace' => $kitNamespace,
                    'name' => $presenter,
                    'type' => 'extend',
                ]);
            });
    }

    protected function kitHasControllers(string $starterKitsDirectory, string $kitPath): bool
    {
        return $this->filesystem->exists($starterKitsDirectory . '/' . $kitPath . '/Http/Controllers');
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
