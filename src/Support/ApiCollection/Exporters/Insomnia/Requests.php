<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Support\ApiCollection\Exporters\Insomnia;

use Illuminate\Routing\Router;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use XtendPackages\RESTPresenter\Support\ApiCollection\Concerns\InteractsWithRoutes;

class Requests
{
    use InteractsWithRoutes;

    protected array $groupIds;

    protected array $resourceIds;

    protected array $schema;

    protected Collection $routes;

    protected string $workspaceId;

    public function __construct(protected Router $router)
    {
        $this->routes = $this->getRoutes();
    }

    public function handle(array $schema, callable $next): array
    {
        $this->schema = $schema;

        $this->workspaceId = $schema['resources'][0]['_id'];

        $this->routes->each(
            fn ($route) => $this->parseRoute($route),
        );

        return $next($this->schema);
    }

    protected function resourcesRoute(string $method, Stringable $uri): void
    {
        $this->schema['resources'][] = [
            '_id' => 'fld_'.uniqid(),
            'parentId' => $this->workspaceId,
            'modified' => now()->toIso8601String(),
            'created' => now()->toIso8601String(),
            'url' => '{{ _.base_url }}/'.$uri,
            'name' => 'Resources',
            'description' => '',
            'method' => strtoupper($method),
            'body' => [
                'mimeType' => 'application/json',
                'text' => json_encode(['key' => 'value'], JSON_PRETTY_PRINT),
            ],
            'parameters' => [],
            'authentication' => [],
            'metaSortKey' => -1,
            '_type' => 'request',
        ];
    }

    protected function apiRouteGrouped(string $method, Stringable $uri, string $group): void
    {
        $this->ensureGroupExists($group);

        $this->addItemToGroup(
            method: $method,
            uri: $uri->value(),
            group: $group,
        );
    }

    protected function ensureGroupExists(string $group): void
    {
        if (! collect($this->schema['resources'])->contains('name', $group)) {
            $this->schema['resources'][] = [
                '_id' => 'fld_'.uniqid(),
                'parentId' => $this->workspaceId,
                'modified' => now()->toIso8601String(),
                'created' => now()->toIso8601String(),
                'name' => $group,
                'description' => '',
                'environment' => [],
                'environmentPropertyOrder' => null,
                'metaSortKey' => -1,
                '_type' => 'request_group',
            ];

            $this->groupIds[$group] = collect($this->schema['resources'])->last()['_id'];
        }
    }

    protected function addItemToGroup(string $method, string $uri, string $group): void
    {
        if (! collect($this->schema['resources'])->contains('name', $uri)) {
            $this->schema['resources'][] = [
                '_id' => 'req_'.uniqid(),
                'parentId' => $this->groupIds[$group],
                'modified' => now()->toIso8601String(),
                'created' => now()->toIso8601String(),
                'url' => '{{ _.base_url }}/'.$uri,
                'name' => $uri,
                'description' => '',
                'method' => strtoupper($method),
                'body' => [
                    'mimeType' => 'application/json',
                    'text' => json_encode(['key' => 'value'], JSON_PRETTY_PRINT),
                ],
                'parameters' => [],
                'headers' => [
                    [
                        'name' => config('rest-presenter.api.presenter_header'),
                        'value' => strtolower(Str::singular($group)),
                        'disabled' => true,
                    ],
                ],
                'authentication' => [],
                'metaSortKey' => -1,
                '_type' => 'request',
            ];

            $this->resourceIds[$uri] = collect($this->schema['resources'])->last()['_id'];
        }
    }
}
