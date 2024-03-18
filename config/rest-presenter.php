<?php

use Illuminate\Routing\Middleware\SubstituteBindings;
use XtendPackages\RESTPresenter\Data\Response\DefaultResponse;
use XtendPackages\RESTPresenter\Resources\Users\Filters;
use XtendPackages\RESTPresenter\Resources\Users\Presenters;

return [
    'generator' => [
        'path' => env('REST_PRESENTER_GENERATOR_PATH', 'app/Api'),
        'namespace' => env('REST_PRESENTER_GENERATOR_NAMESPACE', 'App\Api'),
        'ts_types_path' => env('REST_PRESENTER_GENERATOR_TS_TYPES_PATH', 'resources/ts/types'),
        'test_path' => env('REST_PRESENTER_GENERATOR_TEST_PATH', 'tests/Feature/Api/v1'),
        'test_namespace' => env('REST_PRESENTER_GENERATOR_TEST_NAMESPACE', 'Tests\Feature\Api\v1'),
        // Currently we only support PEST testing framework. Other testing frameworks will be supported in the future.
        'test_framework' => 'pest',
        'structure' => [
            'actions' => 'Actions',
            'concerns' => 'Concerns',
            'contracts' => 'Contracts',
            'data' => 'Data',
            'enums' => 'Enums',
            'exceptions' => 'Exceptions',
            'resources' => 'Resources',
            'support' => 'Support',
        ],
    ],
    'api' => [
        'version' => env('REST_PRESENTER_API_VERSION', 'v1'),
        'prefix' => env('REST_PRESENTER_API_PREFIX', 'api'),
        'name' => env('REST_PRESENTER_API_NAME', 'API'),
        'debug' => env('REST_PRESENTER_API_DEBUG', true),
        'presenter_header' => env('REST_PRESENTER_API_PRESENTER_HEADER', 'X-REST-PRESENTER'),
        'middleware' => [
            // @todo implement middleware + test for VerifyApiKey::class,
            SubstituteBindings::class,
        ],
    ],
    'auth' => [
        'key' => env('REST_PRESENTER_AUTH_API_KEY', 'rest-presenter-secret-key'),
        'token_name' => env('REST_PRESENTER_API_TOKEN_NAME', 'rest-presenter-api-token'),
        'key_header' => env('REST_PRESENTER_API_KEY_HEADER', 'X-REST-PRESENTER-API-KEY'),
        // Note should only be used in development environment
        'impersonate_password' => env('REST_PRESENTER_API_IMPERSONATE_PASSWORD', 'impersonate'),
    ],
    'data' => [
        'response' => DefaultResponse::class,
    ],
    'presenter' => [
        'default' => 'Default',
        'namespace' => 'Presenters',
        'path' => 'Presenters',
    ],
    'resources' => [
        'auth' => [
            // @todo: Add auth overrides here
        ],
        'user' => [
            'model' => config('auth.providers.users.model'),
            'actions' => [
                // @todo: Add actions here
            ],
            'filters' => [
                'email_verified_at' => Filters\UserEmailVerified::class,
            ],
            'presenters' => [
                'profile' => Presenters\Profile::class,
                'user' => Presenters\User::class,
            ],
        ],
    ],
];
