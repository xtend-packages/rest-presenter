<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use XtendPackages\RESTPresenter\Models\Endpoint;
use XtendPackages\RESTPresenter\StarterKits\Filament\Resources\EndpointResource\Pages;

class EndpointResource extends Resource
{
    protected static ?string $model = Endpoint::class;

    protected static ?string $slug = 'endpoints';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'heroicon-o-server-stack';

    protected static ?string $navigationLabel = 'Endpoints';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->options([
                        'GET' => 'GET',
                        'POST' => 'POST',
                        'PUT' => 'PUT',
                        'DELETE' => 'DELETE',
                        'PATCH' => 'PATCH',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('endpoint')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_authenticated')
                    ->label('Authenticated')
                    ->default(false),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }

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
                Tables\Columns\TextColumn::make('uri')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => config('app.url').$state)
                    ->weight('medium')
                    ->alignLeft(),
                Tables\Columns\TextColumn::make('route')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->alignLeft(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'GET' => 'success',
                        'POST' => 'info',
                        'PUT' => 'warning',
                        'DELETE' => 'danger',
                        'PATCH' => 'gray',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->alignLeft(),
                Tables\Columns\IconColumn::make('is_authenticated')
                    ->boolean()
                    ->label('Authenticated')
                    ->alignCenter(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active')
                    ->alignCenter(),
            ])
            ->filters([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageEndpoints::route('/'),
        ];
    }
}
