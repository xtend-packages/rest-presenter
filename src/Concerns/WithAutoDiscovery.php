<?php

namespace XtendPackages\RESTPresenter\Concerns;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Symfony\Component\Finder\SplFileInfo;

trait WithAutoDiscovery
{
    protected function autoDiscover(string $path, $isKit = false): void
    {
        $fileSystem = app(Filesystem::class);
        if (! $fileSystem->isDirectory($path)) {
            return;
        }

        collect($fileSystem->allFiles($path))
            ->filter(fn (SplFileInfo $file) => Str::endsWith($file->getFilename(), 'ResourceController.php'))
            ->mapWithKeys(function (SplFileInfo $file) {
                $name = Str::of(basename($file->getRelativePath()))
                    ->replace('/', '.')
                    ->kebab()
                    ->value();

                $controller = Str::of($file->getRealPath())
                    ->replace(app()->path('Api'), config('rest-presenter.generator.namespace'))
                    ->replace('/', '\\')
                    ->replace('.php', '')
                    ->value();

                return [
                    $name => $controller,
                ];
            })
            ->each(function ($controller, $name) use ($isKit) {
                $kit = Str::of($controller)
                    ->remove(config('rest-presenter.generator.namespace') . '\\StarterKits\\')
                    ->before('\\')
                    ->lower()
                    ->value();

                return Route::name($isKit ? $kit . '.' : null)
                    ->prefix($isKit ? $kit : null)
                    ->group(fn () => $this->resource($name, $controller));
            });
    }
}
