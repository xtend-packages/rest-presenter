<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Filament\Resources\UserResource\Pages;

use Filament\Resources\Pages\ListRecords;
use XtendPackages\RESTPresenter\StarterKits\Filament\Resources\UserResource;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;
}
