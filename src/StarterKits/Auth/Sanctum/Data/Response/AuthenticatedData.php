<?php

namespace XtendPackages\RESTPresenter\StarterKits\Auth\Sanctum\Data\Response;

use Spatie\LaravelData\Data;
use XtendPackages\RESTPresenter\Resources\Users\Data\Response\UserData;

class AuthenticatedData extends Data
{
    public function __construct(
        public UserData $user,
        public string $token,
    ) {
    }
}