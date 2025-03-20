<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use XtendPackages\RESTPresenter\Models\WebhookSubscription;
use XtendPackages\RESTPresenter\StarterKits\Filament\Resources\WebhookSubscriptionResource\Pages;

final class WebhookSubscriptionResource extends Resource
{
    protected static ?string $model = WebhookSubscription::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell';

    protected static ?string $navigationGroup = 'API';

    protected static ?int $navigationSort = 200;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('event_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('target_url')
                    ->required()
                    ->url()
                    ->maxLength(255),
                Forms\Components\TextInput::make('subscribable_type')
                    ->label('Resource Type')
                    ->maxLength(255),
                Forms\Components\TextInput::make('subscribable_id')
                    ->label('Resource ID')
                    ->numeric(),
                Forms\Components\Toggle::make('active')
                    ->required()
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('target_url')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('subscribable_type')
                    ->label('Resource Type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subscribable_id')
                    ->label('Resource ID')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('event_type')
                    ->options(function () {
                        return WebhookSubscription::distinct()->pluck('event_type', 'event_type')->toArray();
                    }),
                Tables\Filters\TernaryFilter::make('active'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Webhook Subscription Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('event_type'),
                        Infolists\Components\TextEntry::make('target_url')
                            ->url(),
                        Infolists\Components\TextEntry::make('secret')
                            ->password(),
                        Infolists\Components\IconEntry::make('active')
                            ->boolean(),
                    ])
                    ->columns(2),
                
                Infolists\Components\Section::make('Resource Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('subscribable_type')
                            ->label('Resource Type'),
                        Infolists\Components\TextEntry::make('subscribable_id')
                            ->label('Resource ID'),
                    ])
                    ->columns(2)
                    ->visible(fn ($record) => $record->subscribable_type !== null),
                
                Infolists\Components\Section::make('Timestamps')
                    ->schema([
                        Infolists\Components\TextEntry::make('created_at')
                            ->dateTime(),
                        Infolists\Components\TextEntry::make('updated_at')
                            ->dateTime(),
                    ])
                    ->columns(2),
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
            'index' => Pages\ListWebhookSubscriptions::route('/'),
            'create' => Pages\CreateWebhookSubscription::route('/create'),
            'view' => Pages\ViewWebhookSubscription::route('/{record}'),
            'edit' => Pages\EditWebhookSubscription::route('/{record}/edit'),
        ];
    }
}
