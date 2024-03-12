<?php

namespace XtendPackages\RESTPresenter\Data\Response;

use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Data;

class DefaultResponse extends Data
{
    public function __construct(
        public ModelData $data,
        public string $presenter,
    ) {
    }

    public static function fromModel(Model $model): self
    {
        return new self(
            data: ModelData::from([
                'id' => $model->id,
                'name' => $model->name ?? null,
                'createdAt' => $model->created_at,
                'updatedAt' => $model->updated_at,
                'attributes' => $model->getAttributes(),
            ]),
            presenter: 'Default',
        );
    }
}
