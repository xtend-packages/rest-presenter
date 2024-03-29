<?php

namespace XtendPackages\RESTPresenter\Resources\Users;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use XtendPackages\RESTPresenter\Resources\ResourceController;

class UserResourceController extends ResourceController
{
    public function index(Request $request): Collection
    {
        $users = $this->getModelQueryInstance()->get();

        return $users->map(
            fn ($user) => $this->present($request, $user),
        );
    }

    public function show(Request $request, User $user): Data
    {
        return $this->present($request, $user);
    }

    public function filters(): array
    {
        return config('rest-presenter.resources.user.filters', [
            'email_verified_at' => Filters\UserEmailVerified::class,
        ]);
    }

    public function presenters(): array
    {
        return config('rest-presenter.resources.user.presenters', [
            'profile' => Presenters\Profile::class,
            'user' => Presenters\User::class,
        ]);
    }
}
