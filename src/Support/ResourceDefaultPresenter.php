<?php

namespace XtendPackages\RESTPresenter\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Spatie\LaravelData\Data;
use XtendPackages\RESTPresenter\Concerns\InteractsWithPresenter;
use XtendPackages\RESTPresenter\Contracts\Presentable;
use XtendPackages\RESTPresenter\Data\Response\DefaultResponse;

class ResourceDefaultPresenter implements Presentable
{
    use InteractsWithPresenter;

    public function __construct(
        private Request $request,
        private readonly ?Model $model,
    ) {}

    public function transform(): Data
    {
        return DefaultResponse::from($this->model);
    }
}
