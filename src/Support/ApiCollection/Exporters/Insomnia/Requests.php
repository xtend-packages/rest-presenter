<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Support\ApiCollection\Exporters\Insomnia;

use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use XtendPackages\RESTPresenter\Support\ApiCollection\Concerns\InteractsWithRoutes;

class Requests
{
    use InteractsWithRoutes;

    /**
     * @var array<string, string>
     */
    protected array $groups;

    /**
     * @var array<string, string>
     */
    protected array $resourceIds;

    /**
     * @var array<string, mixed>
     */
    protected array $schema;

    /**
     * @var Collection<string, mixed>
     */
    protected Collection $routes;

    protected string $workspaceId;

    public function __construct(protected Router $router)
    {
        $this->routes = $this->getRoutes();
    }

    /**
     * @param  array<string, array<mixed>>  $schema
     * @return array<string, mixed>
     */
    public function handle(array $schema, callable $next): array
    {
        $this->schema = $schema;

        $workspace = type($schema['resources'][0])->asArray();
        $this->workspaceId = $workspace['_id'];

        $this->routes->each(
            fn ($route) => $this->parseRoute(
                route: type($route)->as(Route::class),
            ),
        );

        return $next($this->schema);
    }

    protected function resourcesRoute(string $method, Stringable $uri): void
    {
        // @phpstan-ignore-next-line
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
        // @phpstan-ignore-next-line
        if (! collect($this->schema['resources'])->contains('name', $group)) {
            // @phpstan-ignore-next-line
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

            // @phpstan-ignore-next-line
            $this->groups[$group] = collect($this->schema['resources'])->last()['_id'];
        }
    }

    protected function addItemToGroup(string $method, string $uri, string $group): void
    {
        // @phpstan-ignore-next-line
        if (! collect($this->schema['resources'])->contains('name', $uri)) {
            // @phpstan-ignore-next-line
            $this->schema['resources'][] = [
                '_id' => 'req_'.uniqid(),
                'parentId' => $this->groups[$group],
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
                        'name' => 'Accept',
                        'value' => 'application/json',
                    ],
                    [
                        'name' => config('rest-presenter.auth.key_header'),
                        'value' => config('rest-presenter.auth.key'),
                        'disabled' => config('rest-presenter.auth.enable_api_key') === false,
                    ],
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

            // @phpstan-ignore-next-line
            $this->resourceIds[$uri] = collect($this->schema['resources'])->last()['_id'];
        }
    }
}
