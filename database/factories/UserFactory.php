<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Factories;

use XtendPackages\RESTPresenter\Models\User;

final class UserFactory extends \Orchestra\Testbench\Factories\UserFactory
{
    protected $model = User::class;
}
