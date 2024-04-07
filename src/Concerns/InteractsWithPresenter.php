<?php

namespace XtendPackages\RESTPresenter\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\LaravelData\Data;
use XtendPackages\RESTPresenter\Contracts\Presentable;
use XtendPackages\RESTPresenter\Exceptions\PresenterNotFoundException;
use XtendPackages\RESTPresenter\Support\ResourceDefaultPresenter;

trait InteractsWithPresenter
{
    protected function makePresenter(Request $request, ?Model $model): Presentable
    {
        $presenter = $this->getPresenterNamespace(
            fromRequest: $this->getPresenterFromRequestHeader(),
        );

        return app($presenter, [
            'request' => $request,
            'model' => $model,
        ]);
    }

    protected function getPresenterNamespace(string $fromRequest): string
    {
        $namespace = config('rest-presenter.generator.namespace');
        $xtendPresenter = Str::of($fromRequest)->replace('XtendPackages\RESTPresenter', $namespace)->value();
        $extendPresenterFile = Str::of($fromRequest)->replace('XtendPackages\RESTPresenter', '')
            ->replace('\\', '/')
            ->prepend(app()->path('Api'))
            ->append('.php');

        return file_exists($extendPresenterFile) ? $xtendPresenter : $fromRequest;
    }

    protected function present(Request $request, ?Model $model): Data
    {
        return $this->makePresenter(
            request: $request,
            model: $model,
        )->transform();
    }

    protected function getPresenters(): array
    {
        return array_merge([
            'default' => ResourceDefaultPresenter::class,
        ], method_exists($this, 'presenters') ? $this->presenters() : []);
    }

    /**
     * @throws \Exception
     */
    protected function getPresenterFromRequestHeader(): string
    {
        $headerName = strtolower(config('rest-presenter.api.presenter_header', 'x-rest-presenter'));
        $presenter = Str::kebab(request()->headers->get($headerName, 'default'));

        if ($presenter && ! array_key_exists($presenter, $this->getPresenters())) {
            throw new PresenterNotFoundException($presenter);
        }

        return $this->getPresenters()[$presenter];
    }
}
