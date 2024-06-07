<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use XtendPackages\RESTPresenter\Models\Endpoint;
use XtendPackages\RESTPresenter\Models\TestReport;
use XtendPackages\RESTPresenter\StarterKits\Filament\Resources\TestCoverageResource\Pages;
use XtendPackages\RESTPresenter\StarterKits\Filament\Resources\TestCoverageResource\Widgets\TestReportStatsWidget;

class TestReportResource extends Resource
{
    protected static ?string $model = TestReport::class;

    protected static ?string $slug = 'test-report';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';

    protected static ?string $navigationLabel = 'API Test Report';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?int $navigationSort = 2;

    public static function table(Table $table): Table
    {
        return $table
            ->defaultGroup('group')
            ->groups([
                Group::make('group')
                    ->collapsible()
                    ->getDescriptionFromRecordUsing(fn (Endpoint $record): string => Str::of($record->route)->beforeLast('.')->value())
                    ->titlePrefixedWithLabel(false),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->alignLeft(),
                Tables\Columns\TextColumn::make('filename')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->alignLeft(),
                Tables\Columns\TextColumn::make('duration')
                    ->sortable()
                    ->weight('medium')
                    ->alignLeft(),
                Tables\Columns\IconColumn::make('skipped')
                    ->boolean()
                    ->alignCenter(),
                Tables\Columns\IconColumn::make('failed')
                    ->boolean()
                    ->alignCenter(),
                Tables\Columns\IconColumn::make('passing')
                    ->boolean()
                    ->alignCenter(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            TestReportStatsWidget::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTestReport::route('/'),
        ];
    }
}
