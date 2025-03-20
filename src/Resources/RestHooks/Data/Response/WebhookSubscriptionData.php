<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Resources\RestHooks\Data\Response;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

final class WebhookSubscriptionData extends Data
{
    public function __construct(
        public int $id,
        public string $event_type,
        public string $target_url,
        public string $secret,
        public ?string $subscribable_type = null,
        public ?int $subscribable_id = null,
        public bool $active = true,
        #[WithCast(DateTimeInterfaceCast::class)]
        public Carbon $created_at,
        #[WithCast(DateTimeInterfaceCast::class)]
        public Carbon $updated_at,
    ) {}
}
