<?php

namespace XtendPackages\RESTPresenter\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see XtendRouter::resources
 * @method static resources(array $resources, array $options = [])
 *
 * @see XtendRouter::resource
 * @method static resource(string $name, string $controller, array $options = [])
 *
 * @see XtendRouter::auth
 * @method static auth(string $httpVerb, string $uri, string $controller, string $name, ?array $middleware = null)
 *
 * @see \XtendPackages\RESTPresenter\Support\XtendRouter
 */
class XtendRoute extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'xtend-router';
    }
}
