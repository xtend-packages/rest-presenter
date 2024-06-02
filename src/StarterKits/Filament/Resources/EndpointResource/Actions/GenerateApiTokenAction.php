<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Filament\Resources\EndpointResource\Actions;

use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

class GenerateApiTokenAction
{
    public function __invoke(): void
    {
        $config = [
            'abilities' => type(config('rest-presenter.auth.abilities'))->asArray(),
            'tokenName' => type(config('rest-presenter.auth.token_name'))->asString(),
        ];

        $token = auth()->user()->createToken(
            name: $config['tokenName'],
            abilities: $config['abilities'],
        )->plainTextToken;

        Notification::make()
            ->info()
            ->title('Token Generated')
            ->body(__('Please copy bearer the token below to use with your API authenticated requests. <br><br><strong>:token</strong>', ['token' => $token]))
            ->persistent()
            ->actions([
                Action::make('copy')
                    ->label('Copy Token')
                    ->icon('heroicon-o-clipboard')
                    ->extraAttributes([
                        'x-on:click' => 'window.navigator.clipboard.writeText(`'.$token.'`)',
                    ])
                    ->button(),
            ])
            ->send();
    }
}
