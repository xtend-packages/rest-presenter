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
        $scannedPaths = $this->getScanPathsWithNamespaces();
        $allDefinitions = collect();

        foreach ($scannedPaths as $path => $namespace) {
            $files = $this->filesystem->allFiles($path);

            $pathDefinitions = collect($files)
                ->filter(fn (SplFileInfo $file): bool => Str::startsWith($file->getFilename(), $filenamePrefix))
                ->map(fn (SplFileInfo $file): string => $this->getClassFromPath($file->getRealPath(), $path, $namespace))
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

            $allDefinitions = $allDefinitions->mergeRecursive($pathDefinitions);
        }

        return $allDefinitions;
    }

    /**
     * @return array<string, string>
     */
    protected function getScanPathsWithNamespaces(): array
    {
        $paths = collect([app_path() => 'App']);
        $configuredPaths = collect(config('rest-presenter.scan_paths', []))
            ->flatMap(function ($path) {
                $absolutePath = base_path($path);

                if (Str::contains($path, '*')) {
                    $matchedPaths = collect(glob($absolutePath))
                        ->filter(fn($path) => is_dir($path))
                        ->mapWithKeys(fn($path) => [$path => $this->pathToNamespace($path)]);

                    return $matchedPaths->all();
                }

                return [$absolutePath => $this->pathToNamespace($absolutePath)];
            });

        return $paths->merge($configuredPaths)->all();
    }

    protected function pathToNamespace(string $path): ?string
    {
        if ($path === app_path()) {
            return 'App';
        }

        $relativePath = str_replace(base_path(), '', $path);
        if (Str::endsWith($relativePath, '/app')) {
            return Str::of($relativePath)
                ->trim('/')
                ->before('/app')
                ->studly()
                ->replace('/', '\\')
                ->value();
        }

        return null;
    }

    protected function getClassFromPath(string $path, string $basePath, string $namespace): string
    {
        return Str::of($path)
            ->replace([$basePath.'/', '.php'], ['', ''])
            ->replace('/', '\\')
            ->prepend($namespace.'\\')
            ->value();
    }
}
