<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Filament\Resources\UserResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use XtendPackages\RESTPresenter\StarterKits\Filament\Resources\UserResource;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;
}
