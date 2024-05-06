<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Support\ApiCollection\Concerns;

use Closure;
use Illuminate\Routing\Route;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionException;
use ReflectionFunction;

trait InteractsWithRoutes
{
    /**
     * @return Collection<string, mixed>
     */
    public function getRoutes(): Collection
    {
        return collect($this->router->getRoutes()) // @phpstan-ignore-line
            ->filter(fn (Route $route): bool => $this->onlyRestPresenterRoutes($route));
    }

    protected function onlyRestPresenterRoutes(Route $route): bool
    {
        $prefix = config('rest-presenter.api.prefix');
        $version = config('rest-presenter.api.version');

        return Str::startsWith($route->uri(), $prefix.'/'.$version);
    }

    protected function parseRoute(Route $route): void
    {
        $methods = array_filter($route->methods(), fn ($value): bool => $value !== 'HEAD');

        foreach ($methods as $method) {

            // @phpstan-ignore-next-line
            if (! $this->getReflectionMethod($route->getAction())) {
                continue;
            }

            $uri = Str::of($route->uri())->replaceMatches('/{([[:alnum:]]+)}/', ':$1');
            $group = ucfirst((string) Str::of($uri->value())->beforeLast('/:')->explode('/')->last());

            if ($group === 'Resources') {
                // @phpstan-ignore-next-line
                $this->resourcesRoute($method, $uri);

                return;
            }

            // @phpstan-ignore-next-line
            $this->apiRouteGrouped($method, $uri, $group);
        }
    }

    /**
     * @param  array<string, mixed>  $routeAction
     *
     * @throws ReflectionException
     */
    private function getReflectionMethod(array $routeAction): ?object
    {
        if ($routeAction['uses'] instanceof Closure) {
            return new ReflectionFunction($routeAction['uses']);
        }

        $routeData = explode('@', type($routeAction['uses'])->asString());
        $reflection = new ReflectionClass($routeData[0]); // @phpstan-ignore-line

        if (! $reflection->hasMethod($routeData[1])) {
            return null;
        }

        return $reflection->getMethod($routeData[1]);
    }
}
