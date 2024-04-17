<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Resources\Users\Presenters;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\LaravelData\Data;
use XtendPackages\RESTPresenter\Contracts\Presentable;
use XtendPackages\RESTPresenter\Resources\Users\Data\Response\ProfileData;

final readonly class Profile implements Presentable
{
    public function __construct(
        private ?Authenticatable $model,
    ) {
    }

    public function transform(): Data
    {
        return ProfileData::from($this->model);
    }
}
