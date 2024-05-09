<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Support\Tests;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\Sanctum;
use ReflectionClass;
use XtendPackages\RESTPresenter\Models\User;

if (! function_exists('authenticateApiUser')) {
    /**
     * Authenticate an API user.
     *
     * @param  User|null  $user
     * @return User|HasApiTokens
     */
    function authenticateApiUser(?User $user = null): User|HasApiTokens
    {
        return Sanctum::actingAs(
            user: $user ?? User::factory()->create(),
            abilities: ['*'],
        );
    }
}

if (! function_exists('fixture')) {
    /**
     * Get a fixture.
     *
     * @param  string  $name
     * @return array
     */
    function fixture(string $name): array
    {
        $file = file_get_contents(
            filename: base_path("tests/Api/Fixtures/$name.json"),
        );

        if ($file === '' || $file === '0' || $file === false) {
            throw new InvalidArgumentException(
                message: "Cannot find fixture: [$name] at tests/Api/Fixtures/$name.json",
            );
        }

        return json_decode(
            json: $file,
            associative: true,
        );
    }
}

if (! function_exists('getApiHeaderPresenterName')) {
    /**
     * Get the API header presenter name.
     *
     * @return string
     */
    function getApiHeaderPresenterName(): string
    {
        return strtolower((string) config('rest-presenter.api.presenter_header'));
    }
}

if (! function_exists('invokeNonPublicMethod')) {
    /**
     * Invoke a non-public method.
     *
     * @param  object  $object
     * @param  string  $methodName
     * @param  array  $parameters
     * @return mixed
     */
    function invokeNonPublicMethod(object $object, string $methodName, array $parameters = []): mixed
    {
        $reflection = new ReflectionClass($object);
        $method = $reflection->getMethod($methodName);

        return $method->invokeArgs($object, $parameters);
    }
}

if (! function_exists('getValidationRule')) {
    /**
     * Get a validation rule.
     *
     * @param  string  $field
     * @param  string  $key
     * @param  array  $rules
     * @return mixed
     */
    function getValidationRule(string $field, string $key, array $rules): mixed
    {
        return collect($rules)
            ->mapWithKeys(
                fn ($rule, $field) => [
                    $field => collect($rule)->mapWithKeys(
                        fn ($value): array => formatRule($value)
                    ),
                ],
            )
            ->toArray()[$field][$key] ?? null;
    }
}

if (! function_exists('formatRule')) {
    /**
     * Format a rule.
     *
     * @param  string  $rule
     * @return array
     */
    function formatRule(string $rule): array
    {
        $split = Str::of($rule)->explode(':');

        return $split->count() > 1 ? [$split[0] => $split[1]] : [$split[0] => true];
    }
}
