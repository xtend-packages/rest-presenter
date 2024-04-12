<?php

namespace XtendPackages\RESTPresenter\StarterKits\Auth\Sanctum\Actions;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;

class VerifyEmail
{
    public static string $method = 'POST';

    public function __invoke(EmailVerificationRequest $request): JsonResponse
    {
        $request->fulfill();

        return response()->json([
            'message' => __('auth.email_verified'),
        ]);
    }
}