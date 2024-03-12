<?php

namespace XtendPackages\RESTPresenter\Concerns;

use XtendPackages\RESTPresenter\Contracts\Presentable;
use XtendPackages\RESTPresenter\Exceptions\PresenterNotFoundException;
use XtendPackages\RESTPresenter\Support\ResourceDefaultPresenter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

trait InteractsWithPresenter
{
    protected function makePresenter(Request $request, ?Model $model): Presentable
    {
        return app($this->getPresenterFromRequestHeader(), [
            'request' => $request,
            'model' => $model,
        ]);
    }

    protected function present(Request $request, ?Model $model): array
    {
        return $this->makePresenter(
            request: $request,
            model: $model,
        )->transform();
    }

    protected function getPresenters(): array
    {
        return array_merge([
            'Default' => ResourceDefaultPresenter::class
        ], method_exists($this, 'presenters') ? $this->presenters() : []);
    }

    /**
     * @throws \Exception
     */
    protected function getPresenterFromRequestHeader(): string
    {
        $presenter = request()->headers->get('x-rest-presenter', 'Default');

        if ($presenter && !array_key_exists($presenter, $this->getPresenters())) {
            throw new PresenterNotFoundException($presenter);
        }

        return $this->getPresenters()[$presenter];
    }
}
