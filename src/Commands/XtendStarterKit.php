<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use SplFileInfo;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'rest-presenter:xtend-starter-kit')]
final class XtendStarterKit extends Command
{
    protected $signature = 'rest-presenter:xtend-starter-kit {name}';

    protected $description = 'Xtend REST Presenter Starter Kit';

    public function __construct(protected Filesystem $filesystem)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $name = type($this->argument('name'))->asString();
        $starterKitsDirectory = __DIR__.'/../StarterKits';
        $generatedKitsDirectory = config('rest-presenter.generator.path').'/StarterKits';
        $kitFindPath = collect($this->filesystem->allFiles($starterKitsDirectory))
            ->filter(fn ($file) => Str::startsWith($file->getFilename(), $name))
            ->first();

        if (! $kitFindPath) {
            $this->components->warn(__('The starter kit ":kit" path was not found!', ['kit' => $name]));

            return self::FAILURE;
        }

        $kitPath = $kitFindPath->getRelativePath();

        if ($this->kitAlreadyExtended($generatedKitsDirectory, $kitPath)) {
            $this->components->warn(__('The starter kit ":kit" has already been extended!', ['kit' => $name]));

            return self::FAILURE;
        }

        if (! app()->runningUnitTests()) {
            $this->filesystem->ensureDirectoryExists($generatedKitsDirectory.'/'.$kitPath);
        }

        $this->generateStarterKit($starterKitsDirectory, $kitPath);
        $this->autoDiscoverResources($kitPath);

        return self::SUCCESS;
    }

    private function generateStarterKit(string $starterKitsDirectory, string $kitPath): void
    {
        $this->extendControllers($starterKitsDirectory, $kitPath);
        $this->extendResources($starterKitsDirectory, $kitPath);

        $this->autoDiscoverResources($kitPath);
    }

    private function extendControllers(string $starterKitsDirectory, string $kitPath): void
    {
        $kitPath = Str::of($kitPath)->beforeLast('/Base')->value();
        if ($this->kitCanExtendControllers($starterKitsDirectory, $kitPath)) {
            collect($this->filesystem->allFiles($starterKitsDirectory.'/'.$kitPath.'/Http/Controllers'))
                ->filter(fn (SplFileInfo $file) => Str::endsWith($file->getFilename(), 'Controller.php'))
                ->each(function (SplFileInfo $file) use ($kitPath): void {
                    $path = $file->getRelativePathName();
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

    private function extendResources(string $starterKitsDirectory, string $kitPath): void
    {
        $kitPath = Str::of($kitPath)->beforeLast('/Base')->value();
        if ($this->kitCanExtendResources($starterKitsDirectory, $kitPath)) {
            collect($this->filesystem->allFiles($starterKitsDirectory.'/'.$kitPath.'/Resources'))
                ->filter(fn (SplFileInfo $file) => Str::endsWith($file->getFilename(), 'ResourceController.php'))
                ->each(function (SplFileInfo $file) use ($starterKitsDirectory, $kitPath): void {
                    $path = $file->getRelativePath();
                    $kitNamespace = Str::of($kitPath)
                        ->replace('/', '\\')->prepend('StarterKits\\')
                        ->append('\\Resources\\'.Str::of($path)->replace('/', '\\')->beforeLast('\\'))
                        ->value();

                    $resource = basename($path);
                    $resourcePath = $kitPath.'/Resources/'.$path;
                    $kitNamespace .= class_basename($resource) !== $path ? '\\'.$resource : '';

                    $this->extendActions($starterKitsDirectory, $resourcePath, $kitNamespace);
                    $this->extendPresenters($starterKitsDirectory, $resourcePath, $kitNamespace);

                    $this->call('rest-presenter:make-resource', [
                        'kit_namespace' => $kitNamespace,
                        'model' => config('rest-presenter.resources.user.model'),
                        'name' => Str::singular(basename($path)),
                        'type' => 'extend',
                    ]);
                });
        }
    }

    private function autoDiscoverResources(string $kitPath): void
    {
        $kitNamespace = Str::of($kitPath)->replace('/', '\\')->value();
        $supportedKit = match ($kitNamespace) {
            'Auth\\Sanctum\\Base' => 'SanctumStarterKit',
            'Filament\\Base' => 'FilamentStarterKit',
            default => false,
        };

        if ($supportedKit === false) {
            return;
        }

        /** @var \XtendPackages\RESTPresenter\Base\StarterKit $starterKit */
        $starterKit = resolve('XtendPackages\\RESTPresenter\\StarterKits\\'.$kitNamespace.'\\'.$supportedKit);
        $resources = $starterKit->autoDiscover();

        $this->filesystem->deleteDirectory(config('rest-presenter.generator.path').'/StarterKits/'.$kitPath);

        if (! $resources) {
            $this->components->warn(__('No resources were found for ":supported_kit"', ['supported_kit' => $supportedKit]));

            return;
        }

        $kitNamespace = Str::of($kitPath)
            ->replace('/', '\\')->prepend('StarterKits\\')
            ->before('\\Base')
            ->value();

        collect($resources)->each(function (array $resource, string $resourceNamespace) use ($kitNamespace): void {
            $resourceName = Str::of($resourceNamespace)
                ->classBasename()
                ->singular()
                ->value();

            if ($resource['fields'] !== '' && $resource['fields'] !== '0') {
                $this->call(
                    command: 'rest-presenter:make-presenter',
                    arguments: [
                        'kit_namespace' => $kitNamespace.'\\'.$resourceNamespace,
                        'name' => $resourceName.'Presenter',
                        'type' => 'new',
                        'model' => $resource['model'],
                        'resource' => $resourceName,
                        'fields' => $resource['fields'],
                    ],
                );

                $resourceKey = Str::of($resourceName)
                    ->kebab()
                    ->value();

                $this->call('rest-presenter:make-resource', [
                    'kit_namespace' => $kitNamespace.'\\'.$resourceNamespace,
                    'name' => $resourceName,
                    'model' => $resource['model'],
                    'presenters' => [
                        $resourceKey => $resourceName.'Presenter::class',
                    ],
                    'type' => 'new',
                ]);

                $this->call('rest-presenter:make-test', [
                    'kit_namespace' => $kitNamespace.'\\'.$resourceNamespace,
                    'name' => $resourceName.'ResourceTest',
                    'model' => $resource['model'],
                    'test_type' => 'feature',
                    'type' => 'resource',
                ]);
            }
        });
    }

    private function extendActions(string $starterKitsDirectory, string $resourcePath, string $kitNamespace): void
    {
        if (! $this->filesystem->exists($starterKitsDirectory.'/'.$resourcePath.'/Actions')) {
            $this->components->warn(__('No actions were found for this kit :kit_namespace', ['kit_namespace' => $kitNamespace]));

            return;
        }

        collect($this->filesystem->directories($starterKitsDirectory.'/'.$resourcePath.'/Actions'))
            ->each(function ($path) use ($kitNamespace): void {
                $action = basename($path);

                $this->call('rest-presenter:make-action', [
                    'kit_namespace' => $kitNamespace,
                    'name' => $action,
                    'type' => 'extend',
                ]);
            });
    }

    private function extendPresenters(string $starterKitsDirectory, string $resourcePath, string $kitNamespace): void
    {
        if (! $this->filesystem->exists($starterKitsDirectory.'/'.$resourcePath.'/Presenters')) {
            $this->components->warn(__('No presenters were found for this kit :kit_namespace', ['kit_namespace' => $kitNamespace]));

            return;
        }

        collect($this->filesystem->directories($starterKitsDirectory.'/'.$resourcePath.'/Presenters'))
            ->each(function ($path) use ($kitNamespace): void {
                $presenter = basename($path);

                $this->call('rest-presenter:make-presenter', [
                    'kit_namespace' => $kitNamespace,
                    'name' => $presenter,
                    'type' => 'extend',
                ]);
            });
    }

    private function kitCanExtendControllers(string $starterKitsDirectory, string $kitPath): bool
    {
        return $this->filesystem->exists($starterKitsDirectory.'/'.$kitPath.'/Http/Controllers');
    }

    private function kitCanExtendResources(string $starterKitsDirectory, string $kitPath): bool
    {
        return $this->filesystem->exists($starterKitsDirectory.'/'.$kitPath.'/Resources');
    }

    private function kitAlreadyExtended(string $generatedKitsDirectory, string $kitPath): bool
    {
        return $this->filesystem->exists($generatedKitsDirectory.'/'.$kitPath);
    }
}
