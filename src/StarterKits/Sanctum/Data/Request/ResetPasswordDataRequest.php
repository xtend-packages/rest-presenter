<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Sanctum\Data\Request;

use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Data;

final class ResetPasswordDataRequest extends Data
{
    public function __construct(
        #[Email(Email::RfcValidation)]
        public string $email,
    ) {
    }
}
