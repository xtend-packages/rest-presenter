<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Filament\Resources\TestCoverageResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TestCoverageStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Test Coverage', '100%')
                ->description('Code executed by tests')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Type Coverage', '70%')
                ->description('Code covered by type declarations')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info'),
            Stat::make('Profile', '50%')
                ->description('Slowest Performing Tests')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),
        ];
    }
}
