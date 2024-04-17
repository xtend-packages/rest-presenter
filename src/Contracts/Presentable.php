<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Contracts;

use Spatie\LaravelData\Data;

interface Presentable
{
    public function transform(): Data;
}
