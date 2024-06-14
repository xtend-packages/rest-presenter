<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Sushi\Sushi;

/**
 * @property int $id
 * @property string $group
 * @property string $route
 * @property string $type
 * @property string $uri
 * @property bool $is_authenticated
 */
class Endpoint extends Model
{
    use Sushi;

    /**
     * @return array<mixed>
     */
    public function getRows(): array
    {
        return Http::withOptions(type(['verify' => false])->asArray())
            ->withHeader('X-REST-PRESENTER-API-KEY', type(config('rest-presenter.auth.key'))->asString())
            ->get(route('api.v1.resources'))
            ->collect()->transform(function ($v, $k): array {
                if (! is_array($v)) {
                    throw new InvalidArgumentException('v must be an array');
                }

                $group = Str::of($v['name'])
                    ->beforeLast('.')
                    ->afterLast('.')
                    ->title()
                    ->value();

                if ($group === 'V1') {
                    $group = 'API Resources';
                }

                $authenticatedRoute = false;
                if ($v['middleware'] ?? false) {
                    $authenticatedRoute = collect(type($v['middleware'])->asArray())->contains('auth:sanctum');
                }

                return [
                    'id' => $k + 1,
                    'group' => $group,
                    'route' => $v['name'],
                    'type' => $v['methods'][0],
                    'uri' => $v['uri'],
                    'is_authenticated' => $authenticatedRoute,
                ];
            })->toArray();
    }

    public function isPackageEndpoint(): bool
    {
        return ! $this->isFilamentEndpoint() && ! Str::contains($this->route, 'resource');
    }

    public function isFilamentEndpoint(): bool
    {
        return Str::contains($this->route, 'filament');
    }
}
