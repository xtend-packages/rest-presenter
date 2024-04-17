<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Data\Response;

use Spatie\LaravelData\Data;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

final class MediaData extends Data
{
    private static Media $media;

    public function __construct(
        public int $id,
        public string $uuid,
        public string $name,
        public array $customProperties,
        public int $orderColumn,
        public string $url,
    ) {
    }

    public static function fromModel(Media $media): self
    {
        return new self(
            id: $media->id,
            uuid: $media->uuid,
            name: $media->name,
            customProperties: $media->custom_properties,
            orderColumn: $media->order_column,
            url: $media->getUrl(),
        );
    }
}
