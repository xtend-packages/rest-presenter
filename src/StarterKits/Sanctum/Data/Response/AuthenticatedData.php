<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Sanctum\Data\Response;

use Spatie\LaravelData\Data;
use XtendPackages\RESTPresenter\Resources\Users\Data\Response\UserData;

final class AuthenticatedData extends Data
{
    public function __construct(
        public UserData $user,
        public string $token,
    ) {
    }
}
