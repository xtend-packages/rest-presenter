<?php

namespace XtendPackages\RESTPresenter\Contracts;

interface Presentable
{
    public function transform(): array;
}
