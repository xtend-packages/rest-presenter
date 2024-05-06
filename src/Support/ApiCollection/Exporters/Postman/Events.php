<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Support\ApiCollection\Exporters\Postman;

class Events
{
    /**
     * @param  array<string, mixed>  $schema
     * @return array<string, mixed>
     */
    public function handle(array $schema, callable $next): array
    {
        $schema['event'] = [];

        return $next($schema);
    }
}
