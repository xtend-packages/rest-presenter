<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Filament\Resources\EndpointResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\ActionSize;
use XtendPackages\RESTPresenter\StarterKits\Filament\Resources\EndpointResource;
use XtendPackages\RESTPresenter\StarterKits\Filament\Resources\EndpointResource\Widgets\EndpointStatsWidget;

class ManageEndpoints extends ManageRecords
{
    protected static string $resource = EndpointResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ActionGroup::make([
                Actions\Action::make('insomnia')
                    ->label('Insomnia')
                    ->icon('heroicon-o-arrow-down-tray'),
                Actions\Action::make('postman')
                    ->label('Postman')
                    ->icon('heroicon-o-arrow-down-tray'),
            ])
                ->label('Export Collection')
                ->icon('heroicon-o-circle-stack')
                ->size(ActionSize::Small)
                ->color('gray')
                ->button(),
            Actions\ActionGroup::make([
                Actions\Action::make('generate-api-token')
                    ->form(fn () => resolve(EndpointResource\Forms\GenerateApiTokenForm::class)())
                    ->action(fn (array $data) => resolve(EndpointResource\Actions\GenerateApiTokenAction::class)($data))
                    ->label('Generate Token')
                    ->icon('heroicon-o-key'),
                Actions\Action::make('manage-api-tokens')
                    ->label('Manage Tokens')
                    ->icon('heroicon-s-users')
                    ->action(fn () => $this->redirect(route('filament.rest-presenter.resources.users.view', auth()->id()))),
            ])
                ->label('API Tokens')
                ->icon('heroicon-o-key')
                ->size(ActionSize::Small)
                ->color('info')
                ->button(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            EndpointStatsWidget::class,
        ];
    }
}
