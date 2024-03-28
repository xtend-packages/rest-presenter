<?php

namespace XtendPackages\RESTPresenter\Resources\Users\Data\Response;

use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;

final class ProfileData extends Data
{
    public function __construct(
        public int $id,
        #[Min(3)]
        public string $firstName,
        public ?string $lastName,
        #[Email(Email::RfcValidation)]
        public string $email,
    ) {
    }

    public static function fromModel($model): static
    {
        return new ProfileData(
            id: $model->getKey(),
            firstName: static::firstName($model->name),
            lastName: static::lastName($model->name),
            email: $model->email,
        );
    }

    public static function firstName(string $name): string
    {
        return explode(' ', $name)[0];
    }

    public static function lastName(string $name): ?string
    {
        $nameParts = explode(' ', $name);

        return count($nameParts) > 1 ? $nameParts[1] : null;
    }
}
