<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Support\ApiCollection\Exporters\Postman;

use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use XtendPackages\RESTPresenter\Support\ApiCollection\Concerns\InteractsWithRoutes;

class Items
{
    use InteractsWithRoutes;

    /**
     * @var array<string, mixed>
     */
    protected array $schema;

    /**
     * @var Collection<string, mixed>
     */
    protected Collection $routes;

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
        $this->schema['item'][] = [
            'name' => 'Resources',
            'request' => $this->processRequest(
                $method,
                $uri,
            ),
        ];
    }

    protected function apiRouteGrouped(string $method, Stringable $uri, string $group): void
    {
        $this->ensureGroupExists($group);

        $this->addItemToGroup($method, $uri, $group);
    }

    protected function ensureGroupExists(string $group): void
    {
        $schemaItem = type($this->schema['item'])->asArray();
        if (! collect($schemaItem)->contains('name', $group)) {
            // @phpstan-ignore-next-line
            $this->schema['item'][] = [
                'name' => $group,
                'item' => [],
            ];
        }
    }

    protected function addItemToGroup(string $method, Stringable $uri, string $group): void
    {
        $schemaItem = type($this->schema['item'])->asArray();
        $this->schema['item'] = collect($schemaItem)
            ->map(function (array $item) use ($method, $uri, $group): array {
                if ($item['name'] === $group) {
                    $item['item'][] = [
                        'name' => $uri,
                        'request' => $this->processRequest(
                            $method,
                            $uri,
                            $group,
                        ),
                    ];
                }

                return $item;
            })
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    protected function processRequest(string $method, Stringable $uri, ?string $group = null): array
    {
        return collect([
            'method' => strtoupper($method),
            'header' => collect()
                ->push([
                    'key' => 'Accept',
                    'value' => 'application/json',
                    'type' => 'text',
                ])
                ->push($group !== null && $group !== '' && $group !== '0' ? [
                    'key' => config('rest-presenter.api.presenter_header'),
                    'value' => strtolower(Str::singular($group)),
                    'type' => 'text',
                    'disabled' => true,
                ] : [])
                ->filter()
                ->values()
                ->all(),
            'url' => [
                'raw' => '{{base_url}}/'.$uri,
                'host' => ['{{base_url}}'],
                'path' => $uri->explode('/')->filter()->all(),
                'variable' => $uri
                    ->matchAll('/(?<={)[[:alnum:]]+(?=})/m')
                    ->transform(fn ($variable): array => ['key' => $variable, 'value' => ''])
                    ->all(),
            ],
        ])->all();
    }
}
