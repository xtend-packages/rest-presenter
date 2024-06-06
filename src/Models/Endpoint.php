<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Sushi\Sushi;

class Endpoint extends Model
{
    use Sushi;

    public function getRows(): array
    {
        return Http::withOptions(['verify' => false])
            ->withHeader('X-REST-PRESENTER-API-KEY', config('rest-presenter.auth.key'))
            ->get(route('api.v1.resources'))
            ->collect()->transform(function ($v, $k) {

                $group = Str::of($v['name'])
                    ->beforeLast('.')
                    ->afterLast('.')
                    ->title()
                    ->value();

                if ($group === 'V1') {
                    $group = 'API Resources';
                }

                $authenticatedRoute = collect($v['middleware'])->contains('auth:sanctum');

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
}
