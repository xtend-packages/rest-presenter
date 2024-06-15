<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Filament\Resources\EndpointResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use XtendPackages\RESTPresenter\Models\Endpoint;

class EndpointStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $endpoints = Endpoint::all();

        return [
            Stat::make('Total', $endpoints->count())
                ->description('Total Endpoints')
                ->descriptionIcon('heroicon-o-server-stack')
                ->color('info'),
            Stat::make('Package', $endpoints->filter(fn (Endpoint $endpoint) => $endpoint->isPackageEndpoint())->count())
                ->description('RESTPresenter Endpoints')
                ->descriptionIcon('heroicon-o-server-stack')
                ->color('white'),
            Stat::make('Filament', $endpoints->filter(fn (Endpoint $endpoint) => $endpoint->isFilamentEndpoint())->count())
                ->description('Filament Endpoints')
                ->descriptionIcon('heroicon-o-server-stack')
                ->color('warning'),
        ];
    }
}
