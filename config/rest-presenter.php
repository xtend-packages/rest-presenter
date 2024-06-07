<?php

declare(strict_types=1);

use Illuminate\Routing\Middleware\SubstituteBindings;
use XtendPackages\RESTPresenter\Middleware\VerifyApiKey;
use XtendPackages\RESTPresenter\Resources\Users\Presenters;

return [
    'generator' => [
        'path' => env('REST_PRESENTER_GENERATOR_PATH', 'app/Api'),
        'namespace' => env('REST_PRESENTER_GENERATOR_NAMESPACE', 'App\Api'),
        'ts_types_path' => env('REST_PRESENTER_GENERATOR_TS_TYPES_PATH', 'rest-presenter/types'),
        'ts_types_keyword' => env('REST_PRESENTER_GENERATOR_TS_TYPES_KEYWORD', 'interface'),
        'ts_types_trailing_semicolon' => env('REST_PRESENTER_GENERATOR_TS_TYPES_TRAILING_SEMICOLON', true),
        'test_path' => env('REST_PRESENTER_GENERATOR_TEST_PATH', 'tests/Feature/Api/v1'),
        'test_namespace' => env('REST_PRESENTER_GENERATOR_TEST_NAMESPACE', 'Tests\Feature\Api\v1'),
    ],
    'api' => [
        'prefix' => env('REST_PRESENTER_API_PREFIX', 'api'),
        'version' => env('REST_PRESENTER_API_VERSION', 'v1'),
        'name' => env('REST_PRESENTER_API_NAME', 'API'),
        'debug' => env('REST_PRESENTER_API_DEBUG', true),
        'presenter_header' => env('REST_PRESENTER_API_PRESENTER_HEADER', 'X-REST-PRESENTER'),
        'middleware' => [
            VerifyApiKey::class,
            SubstituteBindings::class,
        ],
    ],
    'auth' => [
        'abilities' => ['*'],
        'key' => env('REST_PRESENTER_AUTH_API_KEY', 'rest-presenter-secret-key'),
        'token_name' => env('REST_PRESENTER_AUTH_API_TOKEN_NAME', 'rest-presenter-api-token'),
        'key_header' => env('REST_PRESENTER_AUTH_API_KEY_HEADER', 'X-REST-PRESENTER-API-KEY'),
        'enable_api_key' => env('REST_PRESENTER_AUTH_ENABLE_API_KEY', true),
        'register_data_request_rules' => [
            'name' => env('REST_PRESENTER_AUTH_REGISTER_DATA_NAME', 'required|string|max:255'),
            'email' => env('REST_PRESENTER_AUTH_REGISTER_DATA_EMAIL', 'required|string|email|max:255|unique:users,email'),
            'password' => env('REST_PRESENTER_AUTH_REGISTER_DATA_PASSWORD', 'required|string|min:8|max:255|confirmed'),
        ],
        'login_data_request_rules' => [
            'email' => env('REST_PRESENTER_AUTH_LOGIN_DATA_EMAIL', 'required|string|email|max:255'),
            'password' => env('REST_PRESENTER_AUTH_LOGIN_DATA_PASSWORD', 'required|string|min:8'),
        ],
        'logout_revoke_all_tokens' => env('REST_PRESENTER_AUTH_LOGOUT_REVOKE_ALL_TOKENS', false),
        'rate_limit' => [
            'max_attempts' => env('REST_PRESENTER_AUTH_RATE_LIMIT_MAX_ATTEMPTS', 5),
        ],
    ],
    'exporters' => [
        'provider' => env('REST_PRESENTER_EXPORT_PROVIDER', 'postman'),
        'insomnia' => [
            'workspace' => [
                'name' => env('REST_PRESENTER_EXPORT_INSOMNIA_WORKSPACE_NAME', config('app.name').' (RESTPresenter)'),
                'description' => env('REST_PRESENTER_EXPORT_INSOMNIA_WORKSPACE_DESCRIPTION', config('app.name').' RESTPresenter Workspace'),
            ],
            'environment' => [
                'name' => env('REST_PRESENTER_EXPORT_INSOMNIA_ENVIRONMENT_NAME', config('app.name').' (RESTPresenter)'),
                'base_url' => env('REST_PRESENTER_EXPORT_INSOMNIA_ENVIRONMENT_BASE_URL', config('app.url')),
                'version' => env('REST_PRESENTER_EXPORT_INSOMNIA_ENVIRONMENT_VERSION', 'v1'),
            ],
        ],
        'postman' => [
            'info' => [
                'name' => env('REST_PRESENTER_EXPORT_POSTMAN_INFO_NAME', config('app.name').' (RESTPresenter)'),
                'schema' => env('REST_PRESENTER_EXPORT_POSTMAN_INFO_SCHEMA', 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json'),
            ],
            'authentication' => [
                'method' => env('REST_PRESENTER_EXPORT_POSTMAN_AUTH_METHOD', 'bearer'),
                'token' => env('REST_PRESENTER_EXPORT_POSTMAN_AUTH_TOKEN', 'YOUR_API_TOKEN'),
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
    'resources' => [
        'user' => [
            'model' => config('auth.providers.users.model'),
            'presenters' => [
                'profile' => env('REST_PRESENTER_RESOURCES_USER_PROFILE', Presenters\Profile::class),
                'user' => env('REST_PRESENTER_RESOURCES_USER_USER', Presenters\User::class),
            ],
        ],
    ],
];
