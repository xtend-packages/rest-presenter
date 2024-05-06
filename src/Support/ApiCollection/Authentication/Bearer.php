<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Support\ApiCollection\Authentication;

class Bearer extends AuthenticationMethod
{
    public function prefix(): string
    {
        return 'Bearer';
    }
}
