<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Concerns;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use SplFileInfo;

trait InteractsWithClassDefinitions
{
    /**
     * @param  array<int, string>  $directories
     * @return Collection<string, array<string, string>>
     */
    protected function scanClassDefinitions(array $directories, string $filterPath): Collection
    {
        return collect($this->findDirectoriesPaths($directories)
            ->reduce(fn ($definitions, $directory) => collect($this->filesystem->allFiles($directory))
                ->filter(fn (SplFileInfo $file) => Str::contains($file->getPath(), $filterPath))
                ->reduce(function (array $definitions, SplFileInfo $file) use ($directory, $filterPath): array {
                    $key = Str::of($file->getPath())
                        ->remove($filterPath)
                        ->rtrim('/')
                        ->basename()
                        ->value();

                    $definitions[$key][$file->getBasename()] = $this->getClassFromPath(
                        path: $directory.'/'.$file->getRelativePathname(),
                    );

                    return $definitions;
                }, $definitions), []));
    }

    /**
     * @param  array<int, string>  $directories
     * @return Collection<int, string>
     */
    protected function findDirectoriesPaths(array $directories): Collection
    {
        return collect($this->filesystem->allFiles($this->getBasePath()))
            ->filter(fn (SplFileInfo $file): bool => in_array(basename($file->getPath()), $directories))
            ->map(fn (SplFileInfo $file): string => Str::beforeLast($file->getRealPath(), '/'))
            ->unique()
            ->values();
    }

    protected function getBasePath(): string
    {
        return app_path();
    }

    protected function getClassFromPath(string $path): string
    {
        return Str::of($path)
            ->replace([$this->getBasePath().'/', '.php'], ['', ''])
            ->replace('/', '\\')
            ->prepend('App\\')
            ->value();
    }
}
