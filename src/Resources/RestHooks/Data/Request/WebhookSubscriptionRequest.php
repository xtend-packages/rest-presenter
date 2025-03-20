<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Resources\RestHooks\Data\Request;

use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\Url;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

final class WebhookSubscriptionRequest extends Data
{
    public function __construct(
        #[Required]
        public string $event_type,
        
        #[Required]
        #[Url]
        public string $target_url,
        
        public ?string $resource_type = null,
        
        #[Rule(['nullable', 'numeric'])]
        public ?int $resource_id = null,
    ) {}

    /**
     * @return array<string, array<mixed>>
     */
    public static function rules(ValidationContext $context): array
    {
        return [
            'event_type' => ['required', 'string'],
            'target_url' => ['required', 'url'],
            'resource_type' => ['sometimes', 'string'],
            'resource_id' => ['sometimes', 'numeric'],
        ];
    }
}
