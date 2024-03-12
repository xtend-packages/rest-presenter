<?php

namespace XtendPackages\RESTPresenter\Data\Response;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class ModelData extends Data
{
    public function __construct(
        public int $id,
        public Optional|string|null $name,
        public Carbon $createdAt,
        public Carbon|Optional $updatedAt,
        public Collection $attributes,
    ) {
    }
}
