<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Filament\Resources\WebhookSubscriptionResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use XtendPackages\RESTPresenter\StarterKits\Filament\Resources\WebhookSubscriptionResource;

final class EditWebhookSubscription extends EditRecord
{
    protected static string $resource = WebhookSubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
