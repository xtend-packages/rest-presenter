<?php

namespace XtendPackages\RESTPresenter\Exceptions;

use Exception;

class PresenterNotFoundException extends Exception
{
    public function __construct(string $presenter)
    {
        parent::__construct("Presenter {$presenter} not found");
    }
}
