<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Support\ApiCollection\Exporters\Postman;

use XtendPackages\RESTPresenter\Support\ApiCollection\Authentication;
use XtendPackages\RESTPresenter\Support\ApiCollection\Concerns\HasAuthentication;

class Variables
{
    use HasAuthentication;

    /**
     * @param  array<string, mixed>  $schema
     * @return array<string, mixed>
     */
    public function handle(array $schema, callable $next): array
    {
        $this->resolveAuth('postman');

        $schema['variable'] = [
            [
                'key' => 'base_url',
                'value' => config('app.url'),
            ],
        ];

        if ($this->authentication instanceof Authentication\AuthenticationMethod && $this->authentication->hasToken()) {
            $schema['variable'][] = [
                'key' => 'token',
                'value' => $this->authentication->getToken(),
            ];
        }

        return $next($schema);
    }
}
