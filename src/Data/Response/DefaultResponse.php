<?php

namespace XtendPackages\RESTPresenter\Data\Response;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class DefaultResponse extends Data
{
    public function __construct(
        public int $id,
        public Optional | string | null $name,
        public Carbon | Optional $createdAt,
        public Carbon | Optional $updatedAt,
        public ?array $attributes,
    ) {
    }

    public static function fromModel(Model $model): self
    {
        return new self(
            id: $model->getKey(),
            name: $model->name ?? null,
            createdAt: $model->created_at ?? null,
            updatedAt: $model->updated_at ?? null,
            attributes: $model->getAttributes(),
        );
    }
}
