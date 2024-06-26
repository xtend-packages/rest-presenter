<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Exceptions;

use Exception;

final class PresenterNotFoundException extends Exception
{
    public function __construct(string $presenter)
    {
        parent::__construct("Presenter {$presenter} not found");
    }
}
