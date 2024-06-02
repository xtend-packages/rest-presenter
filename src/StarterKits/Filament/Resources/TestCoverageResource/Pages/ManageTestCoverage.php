<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Filament\Resources\TestCoverageResource\Pages;

use Filament\Resources\Pages\ManageRecords;
use XtendPackages\RESTPresenter\StarterKits\Filament\Resources\TestCoverageResource;
use XtendPackages\RESTPresenter\StarterKits\Filament\Resources\TestCoverageResource\Widgets\TestCoverageStatsWidget;

class ManageTestCoverage extends ManageRecords
{
    protected static string $resource = TestCoverageResource::class;

    protected static ?string $title = 'Test Coverage';

    protected function getHeaderWidgets(): array
    {
        return [
            TestCoverageStatsWidget::class,
        ];
    }
}
