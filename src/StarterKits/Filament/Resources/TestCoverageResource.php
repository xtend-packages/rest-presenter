<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use XtendPackages\RESTPresenter\Models\Endpoint;
use XtendPackages\RESTPresenter\Models\TestCoverage;
use XtendPackages\RESTPresenter\StarterKits\Filament\Resources\TestCoverageResource\Pages;
use XtendPackages\RESTPresenter\StarterKits\Filament\Resources\TestCoverageResource\Widgets\TestCoverageStatsWidget;

class TestCoverageResource extends Resource
{
    protected static ?string $model = TestCoverage::class;

    protected static ?string $slug = 'test-coverage';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'heroicon-m-arrow-trending-up';

    protected static ?string $navigationLabel = 'API Test Coverage';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?int $navigationSort = 3;

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
                Tables\Columns\IconColumn::make('test_coverage')
                    ->boolean()
                    ->alignCenter(),
                Tables\Columns\IconColumn::make('type_coverage')
                    ->boolean()
                    ->alignCenter(),
                Tables\Columns\IconColumn::make('profile')
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
            TestCoverageStatsWidget::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTestCoverage::route('/'),
        ];
    }
}
