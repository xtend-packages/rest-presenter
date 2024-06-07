<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Filament\Resources\UserResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Laravel\Sanctum\PersonalAccessToken;

class TokensRelationManager extends RelationManager
{
    protected static string $relationship = 'tokens';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('abilities')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '*' => 'success',
                        default => 'info',
                    })
                    ->listWithLineBreaks(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->dateTime(),
                Tables\Columns\IconColumn::make('expired')
                    ->boolean()
                    ->color(fn (bool $state): string => $state ? 'danger' : 'gray')
                    ->getStateUsing(fn (PersonalAccessToken $record) => $record->expires_at->isPast()) // @phpstan-ignore-line
                    ->alignCenter(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make('revoke')
                    ->icon('heroicon-o-x-circle')
                    ->label('Revoke Token'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
