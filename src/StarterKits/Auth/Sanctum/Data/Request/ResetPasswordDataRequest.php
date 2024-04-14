<?php

namespace XtendPackages\RESTPresenter\StarterKits\Auth\Sanctum\Data\Request;

use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Data;

class ResetPasswordDataRequest extends Data
{
    public function __construct(
        #[Email(Email::RfcValidation)]
        public string $email,
    ) {
    }
}
