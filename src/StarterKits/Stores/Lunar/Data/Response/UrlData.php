<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Data\Response;

use Lunar\Models\Url;
use Spatie\LaravelData\Data;

final class UrlData extends Data
{
    public function __construct(
        public int $id,
        public int $languageId,
        public string $slug,
    ) {
    }

    public static function fromModel(Url $url): self
    {
        return new self(
            id: $url->id,
            languageId: $url->language_id,
            slug: $url->slug,
        );
    }
}
