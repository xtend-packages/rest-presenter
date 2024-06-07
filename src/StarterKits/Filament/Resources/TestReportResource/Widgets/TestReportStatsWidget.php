<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Filament\Resources\TestCoverageResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TestReportStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Tests', '1k')
                ->description('Total API Tests')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info'),
            Stat::make('Skipped', 20)
                ->description('API Test Profile')
                ->descriptionIcon('heroicon-o-minus-circle')
                ->color('warning'),
            Stat::make('Failed', 80)
                ->description('API Failed Tests')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('danger'),
            Stat::make('Passed', 900)
                ->description('API Passed Tests')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
        ];
    }
}
