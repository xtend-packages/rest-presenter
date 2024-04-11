<?php

namespace XtendPackages\RESTPresenter\StarterKits\Auth\Sanctum\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use XtendPackages\RESTPresenter\StarterKits\Auth\Sanctum\Data\Request\ResetPasswordDataRequest;

class ResetPassword
{
    public static string $method = 'POST';

    public function __invoke(ResetPasswordDataRequest $request): JsonResponse
    {
        $status = Password::sendResetLink(
            $request->toArray(),
        );

        if ($status != Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        return response()->json([
            'status' => __($status),
        ]);
    }
}
