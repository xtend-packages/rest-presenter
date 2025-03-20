<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Filament\Resources\WebhookSubscriptionResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
use XtendPackages\RESTPresenter\StarterKits\Filament\Resources\WebhookSubscriptionResource;

final class CreateWebhookSubscription extends CreateRecord
{
    protected static string $resource = WebhookSubscriptionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['secret'] = Str::random(32);

        return $data;
    }
}
