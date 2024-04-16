<?php

namespace XtendPackages\RESTPresenter\Resources\Users\Data\Response;

use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;

final class ProfileData extends Data
{
    public function __construct(
        public mixed $id,
        #[Min(3)]
        public string $firstName,
        public ?string $lastName,
        #[Email(Email::RfcValidation)]
        public string $email,
    ) {
    }

    /**
     * @template TModel of \XtendPackages\RESTPresenter\Models\User
     * @param TModel $model
     */
    public static function fromModel(Model $model): ProfileData
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
