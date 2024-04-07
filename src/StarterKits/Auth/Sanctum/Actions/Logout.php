<?php

namespace XtendPackages\RESTPresenter\StarterKits\Auth\Sanctum\Actions;

use XtendPackages\RESTPresenter\Models\User;
use XtendPackages\RESTPresenter\StarterKits\Auth\Sanctum\Data\Request\RegisterDataRequest;

class Logout
{
    public function execute(RegisterDataRequest $request): User
    {
        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
    }
}
