<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Filament\Resources\TestCoverageResource\Pages;

use Filament\Resources\Pages\ManageRecords;
use XtendPackages\RESTPresenter\StarterKits\Filament\Resources\TestCoverageResource\Widgets\TestReportStatsWidget;
use XtendPackages\RESTPresenter\StarterKits\Filament\Resources\TestReportResource;

class ManageTestReport extends ManageRecords
{
    protected static string $resource = TestReportResource::class;

    protected static ?string $title = 'Test Report';

    protected function getHeaderWidgets(): array
    {
        return [
            TestReportStatsWidget::class,
        ];
    }
}
