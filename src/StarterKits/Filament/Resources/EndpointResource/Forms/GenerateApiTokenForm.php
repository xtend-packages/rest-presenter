<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Filament\Resources\EndpointResource\Forms;

use Filament\Forms;

class GenerateApiTokenForm
{
    /**
     * @return array<int, mixed>
     */
    public function __invoke(): array
    {
        return [
            Forms\Components\TextInput::make('tokenName')
                ->default('RESTPresenter API Token')
                ->required(),
            Forms\Components\Repeater::make('abilities')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->default('*')
                        ->required(),
                    Forms\Components\Select::make('only')
                        ->options([
                            'create' => 'Create',
                            'read' => 'Read',
                            'update' => 'Update',
                            'delete' => 'Delete',
                        ]),
                ])
                ->grid(3)
                ->required(),
            Forms\Components\DateTimePicker::make('expires_at')
                ->default(now()->addMonth()->setTime(12, 0)->format('Y-m-d H:i:s'))
                ->required(),
        ];
    }
}
