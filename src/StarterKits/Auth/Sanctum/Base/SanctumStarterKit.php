<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Auth\Sanctum\Base;

use XtendPackages\RESTPresenter\Base\StarterKit;
use XtendPackages\RESTPresenter\Concerns\InteractsWithClassDefinitions;
use XtendPackages\RESTPresenter\Concerns\InteractsWithDbSchema;

final class SanctumStarterKit extends StarterKit
{
    use InteractsWithClassDefinitions;
    use InteractsWithDbSchema;

    protected static string $packageName = 'laravel/sanctum';

    /**
     * @var array<string, array<string, mixed>>
     */
    private array $resources = [];

    /**
     * @return array<string, array<string, mixed>>
     */
    public function autoDiscover(): array
    {
        return $this->resources;
    }
}
