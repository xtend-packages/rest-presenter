<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Concerns;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use SplFileInfo;

trait InteractsWithClassDefinitions
{
    /**
     * @return Collection<string, non-empty-array<string, class-string>>
     */
    protected function scanClassDefinitions(string $filenamePrefix, string $removeFromGroupKey, string $parentClass): Collection
    {
        return collect($this->filesystem->allFiles($this->getBasePath()))
            ->filter(fn (SplFileInfo $file): bool => Str::startsWith($file->getFilename(), $filenamePrefix))
            ->map(fn (SplFileInfo $file): string => $this->getClassFromPath($file->getRealPath()))
            ->filter(fn (string $class): bool => is_subclass_of($class, $parentClass))
            ->mapWithKeys(function (string $class) use ($removeFromGroupKey): array {
                $key = Str::of($class)
                    ->remove($removeFromGroupKey)
                    ->beforeLast('\\')
                    ->classBasename()
                    ->value();

                $basename = Str::of($class)
                    ->classBasename()
                    ->value();

                $definitions[$key][$basename] = $class;

                return $definitions;
            });
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
