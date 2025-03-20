<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Filament\Resources\WebhookSubscriptionResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use XtendPackages\RESTPresenter\StarterKits\Filament\Resources\WebhookSubscriptionResource;

final class ViewWebhookSubscription extends ViewRecord
{
    protected static string $resource = WebhookSubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
