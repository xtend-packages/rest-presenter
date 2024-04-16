<?php

namespace XtendPackages\RESTPresenter\StarterKits\Auth\Sanctum\Data\Request;

use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class RegisterDataRequest extends Data
{
    public function __construct(
        #[Rule(['required', 'string', 'max:255'])]
        public string $name,
        #[Email(Email::RfcValidation)]
        public string $email,
        #[Rule(['required', 'confirmed'])]
        public string $password,
    ) {
    }

    /**
     * @return array<string, array<mixed>>
     */
    public static function rules(ValidationContext $context): array
    {
        return type(config('rest-presenter.auth.register_data_request_rules'))->asArray();
    }
}
