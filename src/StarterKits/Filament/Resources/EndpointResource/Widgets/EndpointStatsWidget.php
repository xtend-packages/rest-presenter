<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Filament\Resources\EndpointResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EndpointStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total', 20)
                ->description('Total Endpoints')
                ->descriptionIcon('heroicon-o-server-stack')
                ->color('info'),
            Stat::make('Package', 5)
                ->description('RESTPresenter Endpoints')
                ->descriptionIcon('heroicon-o-server-stack')
                ->color('white'),
            Stat::make('Filament', 15)
                ->description('Filament Endpoints')
                ->descriptionIcon('heroicon-o-server-stack')
                ->color('warning'),
        ];
    }
}
