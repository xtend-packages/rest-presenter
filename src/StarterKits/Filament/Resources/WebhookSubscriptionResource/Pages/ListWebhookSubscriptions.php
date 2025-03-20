<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Filament\Resources\WebhookSubscriptionResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use XtendPackages\RESTPresenter\StarterKits\Filament\Resources\WebhookSubscriptionResource;

final class ListWebhookSubscriptions extends ListRecords
{
    protected static string $resource = WebhookSubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
