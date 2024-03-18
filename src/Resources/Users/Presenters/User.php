<?php

namespace XtendPackages\RESTPresenter\Resources\Users\Presenters;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\LaravelData\Data;
use XtendPackages\RESTPresenter\Contracts\Presentable;
use XtendPackages\RESTPresenter\Resources\Users\Data\Response\UserData;

class User implements Presentable
{
    public function __construct(
        private ?Authenticatable $model,
    ) {}

    public function transform(): Data
    {
        return UserData::from($this->model);
    }
}
