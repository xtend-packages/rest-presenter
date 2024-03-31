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
        public Carbon | Optional | null $createdAt,
        public Carbon | Optional | null $updatedAt,
        public ?array $attributes,
    ) {
    }

    public static function fromModel(Model $model): self
    {
        return new self(
            id: $model->getAttribute('id'),
            name: $model->getAttribute('name'),
            createdAt: $model->getAttribute('created_at'),
            updatedAt: $model->getAttribute('updated_at'),
            attributes: $model->getAttributes(),
        );
    }
}
