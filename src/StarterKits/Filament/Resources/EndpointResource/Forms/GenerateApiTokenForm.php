<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Filament\Resources\EndpointResource\Forms;

use Filament\Forms;
use Filament\Forms\Form;

class GenerateApiTokenForm
{
    public function __invoke()
    {
        return [
            Forms\Components\TextInput::make('token_name')
                ->required(),
            Forms\Components\CheckboxList::make('abilities')
                ->required(),
            Forms\Components\DatePicker::make('expires_at'),
        ];
    }
}
