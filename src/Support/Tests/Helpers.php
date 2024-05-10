<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Support\Tests;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Laravel\Sanctum\Sanctum;
use ReflectionClass;
use XtendPackages\RESTPresenter\Models\User;

if (! function_exists('authenticateApiUser')) {
    /**
     * Authenticate an API user.
     */
    function authenticateApiUser(?User $user = null): Authenticatable
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
     * @return array<mixed>
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

        return type(json_decode(
            json: $file,
            associative: true,
        ))->asArray();
    }
}

if (! function_exists('getApiHeaderPresenterName')) {
    /**
     * Get the API header presenter name.
     */
    function getApiHeaderPresenterName(): string
    {
        return strtolower(type(config('rest-presenter.api.presenter_header'))->asString());
    }
}

if (! function_exists('invokeNonPublicMethod')) {
    /**
     * Invoke a non-public method.
     *
     * @param  array<mixed>  $parameters
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
     * @param  array<string, mixed>  $rules
     */
    function getValidationRule(string $field, string $key, array $rules): mixed
    {
        return collect($rules)
            ->mapWithKeys(
                fn ($rule, $field) => [
                    $field => collect($rule)->mapWithKeys( // @phpstan-ignore-line
                        fn ($value): array => formatRule(type($value)->asString())
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
     * @return array<string, mixed>
     */
    function formatRule(string $rule): array
    {
        $split = Str::of($rule)->explode(':');

        return $split->count() > 1 ? [$split[0] => $split[1]] : [$split[0] => true];
    }
}
