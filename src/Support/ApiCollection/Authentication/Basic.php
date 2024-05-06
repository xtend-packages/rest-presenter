<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Support\ApiCollection\Authentication;

class Basic extends AuthenticationMethod
{
    public function prefix(): string
    {
        return 'Basic';
    }
}
