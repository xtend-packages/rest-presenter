<?php

namespace XtendPackages\RESTPresenter\StarterKits\Auth\Sanctum\Http\Controllers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use XtendPackages\RESTPresenter\Controllers\Controller;
use XtendPackages\RESTPresenter\Data\Response\DefaultResponse;
use XtendPackages\RESTPresenter\StarterKits\Auth\Sanctum\Actions\RegisterUserAction;
use XtendPackages\RESTPresenter\StarterKits\Auth\Sanctum\Data\Request\RegisterDataRequest;

class RegisteredUserController extends Controller
{
    public function store(RegisterDataRequest $request, RegisterUserAction $action): JsonResponse
    {
        $user = $action->execute($request);
        event(new Registered($user));

        return response()->json([
            'token' => $user->createToken('auth_token')->plainTextToken,
            'user' => DefaultResponse::from($user),
        ]);
    }
}
