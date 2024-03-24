<?php

use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\Sanctum;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use XtendPackages\RESTPresenter\Models\User;

function authenticateApiUser(?User $user = null): User | HasApiTokens
{
    return Sanctum::actingAs(
        user: $user ?? User::factory()->create(),
        abilities: ['*'],
    );
}

function fixture(string $name): array
{
    $file = file_get_contents(
        filename: base_path("tests/Api/Fixtures/$name.json"),
    );

    if (! $file) {
        throw new InvalidArgumentException(
            message: "Cannot find fixture: [$name] at tests/Api/Fixtures/$name.json",
        );
    }

    return json_decode(
        json: $file,
        associative: true,
    );
}

function getApiHeaderPresenterName(): string
{
    return strtolower(config('rest-presenter.api.presenter_header'));
}

function invokeNonPublicMethod(object $object, string $methodName, array $parameters = []): mixed
{
    $reflection = new ReflectionClass($object);
    $method = $reflection->getMethod($methodName);

    return $method->invokeArgs($object, $parameters);
}

function getValidationRule(string $field, string $key, array $rules): mixed
{
    return collect($rules)
        ->mapWithKeys(
            fn ($rule, $field) => [
                $field => collect($rule)->mapWithKeys(
                    fn ($value) => formatRule($value)
                ),
            ],
        )
        ->toArray()[$field][$key] ?? null;
}

function formatRule(string $rule): array
{
    $split = Str::of($rule)->explode(':');

    return $split->count() > 1 ? [$split[0] => $split[1]] : [$split[0] => true];
}

function fakeMediaItem(int $id): Media
{
    $media = Mockery::mock(Media::class)
        ->makePartial()
        ->forceFill([
            'id' => $id,
            'uuid' => Str::uuid(),
            'name' => 'Name ' . $id,
            'custom_properties' => ['custom' => 'properties'],
            'order_column' => $id,
        ]);

    $media
        ->shouldReceive('getUrl')
        ->andReturn('http://example.com/' . $id);

    return $media;
}
