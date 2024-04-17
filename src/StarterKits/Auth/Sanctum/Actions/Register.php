<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Auth\Sanctum\Actions;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use XtendPackages\RESTPresenter\Data\Response\DefaultResponse;
use XtendPackages\RESTPresenter\Models\User;
use XtendPackages\RESTPresenter\StarterKits\Auth\Sanctum\Data\Request\RegisterDataRequest;

final class Register
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

        $config = [
            'abilities' => type(config('rest-presenter.auth.abilities'))->asArray(),
            'tokenName' => type(config('rest-presenter.auth.token_name'))->asString(),
        ];

        return response()->json([
            'token' => $user->createToken(
                name: $config['tokenName'],
                abilities: $config['abilities'],
            )->plainTextToken,
            'user' => DefaultResponse::from($user),
            'message' => __('rest-presenter::auth.register_message'),
        ]);
    }
}
