<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Filament\Resources\EndpointResource\Actions;

use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;

class GenerateApiTokenAction
{
    public function __invoke(array $data): void
    {
        if ($data['abilities'] ?? false) {
            $data['abilities'] = collect($data['abilities'])->map(
                fn ($ability) => $ability['name'].($ability['only'] ? ':'.$ability['only'] : ''),
            )->toArray();
        }

        $config = [
            'abilities' => type($data['abilities'] ?? config('rest-presenter.auth.abilities'))->asArray(),
            'tokenName' => type($data['tokenName'] ?? config('rest-presenter.auth.token_name'))->asString(),
        ];

        $token = auth()->user()->createToken(
            name: $config['tokenName'],
            abilities: $config['abilities'],
            expiresAt: Carbon::parse($data['expires_at']) ?? null,
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
