<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Support\ApiCollection\Exporters\Insomnia;

class Environments
{
    public function handle(array $schema, callable $next): array
    {
        $schema['resources'][] = [
            '_id' => 'env_'.uniqid(),
            'parentId' => $schema['resources'][0]['_id'],
            'modified' => now()->toIso8601String(),
            'created' => now()->toIso8601String(),
            'name' => config('rest-presenter.exporters.insomnia.environment.name'),
            'data' => [
                'base_url' => config('rest-presenter.exporters.insomnia.environment.base_url'),
                'version' => config('rest-presenter.exporters.insomnia.environment.version'),
            ],
            'color' => null,
            'isPrivate' => false,
            'metaSortKey' => -1,
            '_type' => 'environment',
        ];

        return $next($schema);
    }
}
