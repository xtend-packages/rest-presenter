<?php

declare(strict_types=1);

use Illuminate\Routing\Middleware\SubstituteBindings;
use XtendPackages\RESTPresenter\Data\Response\DefaultResponse;
use XtendPackages\RESTPresenter\Resources\Users\Filters;
use XtendPackages\RESTPresenter\Resources\Users\Presenters;

return [
    'generator' => [
        'path' => env('REST_PRESENTER_GENERATOR_PATH', 'app/Api'),
        'namespace' => env('REST_PRESENTER_GENERATOR_NAMESPACE', 'App\Api'),
        'ts_types_path' => env('REST_PRESENTER_GENERATOR_TS_TYPES_PATH', 'types'),
        'ts_types_keyword' => env('REST_PRESENTER_GENERATOR_TS_TYPES_KEYWORD', 'interface'),
        'ts_types_trailing_semicolon' => env('REST_PRESENTER_GENERATOR_TS_TYPES_TRAILING_SEMICOLON', true),
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
        'prefix' => env('REST_PRESENTER_API_PREFIX', 'api'),
        'version' => env('REST_PRESENTER_API_VERSION', 'v1'),
        'name' => env('REST_PRESENTER_API_NAME', 'API'),
        'debug' => env('REST_PRESENTER_API_DEBUG', true),
        'presenter_header' => env('REST_PRESENTER_API_PRESENTER_HEADER', 'X-REST-PRESENTER'),
        'middleware' => [
            // @todo implement middleware + test for VerifyApiKey::class,
            SubstituteBindings::class,
        ],
    ],
    'auth' => [
        'abilities' => ['*'],
        'key' => env('REST_PRESENTER_AUTH_API_KEY', 'rest-presenter-secret-key'),
        'token_name' => env('REST_PRESENTER_API_TOKEN_NAME', 'rest-presenter-api-token'),
        'key_header' => env('REST_PRESENTER_API_KEY_HEADER', 'X-REST-PRESENTER-API-KEY'),
        'register_data_request_rules' => [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ],
        'login_data_request_rules' => [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ],
        'logout_revoke_all_tokens' => env('REST_PRESENTER_AUTH_LOGOUT_REVOKE_ALL_TOKENS', false),
        'rate_limit' => [
            'max_attempts' => env('REST_PRESENTER_AUTH_RATE_LIMIT_MAX_ATTEMPTS', 5),
        ],
    ],
    'exporters' => [
        'provider' => env('REST_PRESENTER_EXPORT_PROVIDER', 'insomnia'),
        'insomnia' => [
            'workspace' => [
                'name' => config('app.name').' (RESTPresenter)',
                'description' => config('app.name').' RESTPresenter Workspace',
            ],
            'environment' => [
                'name' => config('app.name').' (RESTPresenter)',
                'base_url' => config('app.url'),
                'version' => 'v1',
            ],
        ],
        'postman' => [
            'info' => [
                'name' => config('app.name').' (RESTPresenter)',
                'schema' => 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json',
            ],
            'auth_middleware' => 'auth:api',
            'authentication' => [
                'method' => env('POSTMAN_EXPORT_AUTH_METHOD', 'Bearer'),
                'token' => env('POSTMAN_EXPORT_AUTH_TOKEN', 'YOUR_API_TOKEN'),
            ],
            'headers' => [
                [
                    'key' => 'Accept',
                    'value' => 'application/json',
                ],
                [
                    'key' => 'Content-Type',
                    'value' => 'application/json',
                ],
            ],
        ],
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
