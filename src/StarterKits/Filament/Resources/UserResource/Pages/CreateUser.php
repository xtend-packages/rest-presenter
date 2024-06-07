<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Filament\Resources\UserResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use XtendPackages\RESTPresenter\StarterKits\Filament\Resources\UserResource;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
