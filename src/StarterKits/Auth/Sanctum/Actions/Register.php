<?php

namespace XtendPackages\RESTPresenter\StarterKits\Auth\Sanctum\Actions;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use XtendPackages\RESTPresenter\Data\Response\DefaultResponse;
use XtendPackages\RESTPresenter\Models\User;
use XtendPackages\RESTPresenter\StarterKits\Auth\Sanctum\Data\Request\RegisterDataRequest;

class Register
{
    public static string $method = 'POST';

    public function __invoke(RegisterDataRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        event(new Registered($user));

        if (method_exists($user, 'notify')) {
            $user->sendEmailVerificationNotification();
        }

        return response()->json([
            'token' => $user->createToken(
                name: config('rest-presenter.auth.token_name'),
                abilities: config('rest-presenter.auth.abilities'),
            )->plainTextToken,
            'user' => DefaultResponse::from($user),
        ]);
    }
}
