<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Data\Response;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

final class DefaultResponse extends Data
{
    /**
     * @param  array<string, mixed>|null  $attributes
     */
    public function __construct(
        public mixed $id,
        public Optional|string|null $name,
        public Carbon|Optional|null $createdAt,
        public Carbon|Optional|null $updatedAt,
        public ?array $attributes,
    ) {
    }

    public static function fromModel(Model $model): self
    {
        $createdAt = $model->getAttribute('created_at') ? type($model->getAttribute('created_at'))->as(Carbon::class) : null;
        $updatedAt = $model->getAttribute('updated_at') ? type($model->getAttribute('updated_at'))->as(Carbon::class) : null;
        $name = type($model->getAttribute('name') ?? '')->asString();

        return new self(
            id: $model->getAttribute('id'),
            name: $name,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
            attributes: collect($model->getAttributes())
                ->except($model->getHidden())
                ->toArray(),
        );
    }
}
