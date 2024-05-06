<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Support\ApiCollection\Exporters\Postman;

use Illuminate\Routing\Router;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use XtendPackages\RESTPresenter\Support\ApiCollection\Concerns\InteractsWithRoutes;

class Items
{
    use InteractsWithRoutes;

    protected array $schema;

    protected Collection $routes;

    public function __construct(protected Router $router)
    {
        $this->routes = $this->getRoutes();
    }

    public function handle(array $schema, callable $next): array
    {
        $this->schema = $schema;

        $this->routes->each(
            fn ($route) => $this->parseRoute($route),
        );

        return $next($this->schema);
    }

    protected function resourcesRoute(string $method, Stringable $uri): void
    {
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
        if (! collect($this->schema['item'])->contains('name', $group)) {
            $this->schema['item'][] = [
                'name' => $group,
                'item' => [],
            ];
        }
    }

    protected function addItemToGroup(string $method, Stringable $uri, string $group): void
    {
        $this->schema['item'] = collect($this->schema['item'])
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
