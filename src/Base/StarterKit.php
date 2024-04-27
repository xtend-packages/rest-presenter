<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Base;

use Composer\InstalledVersions;
use Illuminate\Filesystem\Filesystem;

abstract class StarterKit
{
    protected static string $packageName;

    protected static bool $packageInstalled = false;

    public function __construct(protected Filesystem $filesystem)
    {
        static::$packageInstalled = InstalledVersions::isInstalled(
            packageName: static::$packageName,
        );
    }

    /**
     * @return array<string, array<string, string>>
     */
    abstract public function autoDiscover(): array;
}
