<?php

namespace XtendPackages\RESTPresenter\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Spatie\LaravelData\Data;
use XtendPackages\RESTPresenter\Contracts\Presentable;
use XtendPackages\RESTPresenter\Exceptions\PresenterNotFoundException;
use XtendPackages\RESTPresenter\Support\ResourceDefaultPresenter;

trait InteractsWithPresenter
{
    protected function makePresenter(Request $request, ?Model $model): Presentable
    {
        return app($this->getPresenterFromRequestHeader(), [
            'request' => $request,
            'model' => $model,
        ]);
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
        $presenter = strtolower(request()->headers->get($headerName, 'default'));

        if ($presenter && ! array_key_exists($presenter, $this->getPresenters())) {
            throw new PresenterNotFoundException($presenter);
        }

        return $this->getPresenters()[$presenter];
    }
}
