<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Support\ApiCollection\Exporters\Insomnia;

class Workspace
{
    public function handle(array $schema, callable $next): array
    {
        $schema['resources'][] = [
            '_id' => uniqid('wrk_'),
            'parentId' => null,
            'modified' => now()->toIso8601String(),
            'created' => now()->toIso8601String(),
            'name' => config('rest-presenter.exporters.insomnia.workspace.name'),
            'description' => config('rest-presenter.exporters.insomnia.workspace.description'),
            'scope' => 'collection',
            '_type' => 'workspace',
        ];

        return $next($schema);
    }
}
