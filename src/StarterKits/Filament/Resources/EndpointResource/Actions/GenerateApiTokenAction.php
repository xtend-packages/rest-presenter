<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Filament\Resources\EndpointResource\Actions;

use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;

class GenerateApiTokenAction
{
    /**
     * @param array<string, mixed> $data
     */
    public function __invoke(array $data): void
    {
        if (! auth()->user()) {
            return;
        }

        if ($data['abilities'] ?? false) {
            $data['abilities'] = collect(type($data['abilities'])->asArray())->map(
                fn ($ability): string => $ability['name'].($ability['only'] ? ':'.$ability['only'] : ''),
            )->toArray();
        }

        $config = [
            'abilities' => type($data['abilities'] ?? config('rest-presenter.auth.abilities'))->asArray(),
            'tokenName' => type($data['tokenName'] ?? config('rest-presenter.auth.token_name'))->asString(),
        ];

        $token = auth()->user()->createToken( // @phpstan-ignore-line
            name: $config['tokenName'],
            abilities: $config['abilities'],
            expiresAt: Carbon::parse(type($data['expires_at'])->asString()),
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
