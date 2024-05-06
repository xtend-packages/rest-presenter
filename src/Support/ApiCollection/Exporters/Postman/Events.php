<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Support\ApiCollection\Exporters\Postman;

class Events
{
    public function handle(array $schema, callable $next): array
    {
        $schema['event'] = [
            [
                'listen' => 'test',
                'script' => [
                    'id' => uniqid('scr_'),
                    'exec' => [
                        'type' => 'text/javascript',
                        'exec' => [
                            'pm.test("Status code is 200", function () {',
                            '    pm.response.to.have.status(200);',
                            '});',
                        ],
                    ],
                    'type' => 'text/javascript',
                ],
            ],
        ];

        return $next($schema);
    }
}
