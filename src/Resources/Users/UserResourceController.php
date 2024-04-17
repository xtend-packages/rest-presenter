<?php

namespace XtendPackages\RESTPresenter\Resources\Users;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use XtendPackages\RESTPresenter\Models\User;
use XtendPackages\RESTPresenter\Resources\ResourceController;

class UserResourceController extends ResourceController
{
    public function __construct(Request $request)
    {
        $this->middleware('auth:sanctum');

        parent::__construct($request);
    }

    /**
     * @return Collection<int, Data>
     */
    public function index(Request $request): Collection
    {
        /** @var Collection<int, User> $users */
        $users = $this->getModelQueryInstance()->get();

        return $users->map(
            fn ($user): \Spatie\LaravelData\Data => $this->present($request, $user),
        );
    }

    public function show(Request $request, User $user): Data
    {
        return $this->present($request, $user);
    }

    /**
     * @return array<int, string>
     */
    public function filters(): array
    {
        $filters = config('rest-presenter.resources.user.filters', [
            'email_verified_at' => Filters\UserEmailVerified::class,
        ]);

        assert(is_array($filters));

        return $filters;
    }

    /**
     * @return array<int, string>
     */
    public function presenters(): array
    {
        $presenters = config('rest-presenter.resources.user.presenters', [
            'profile' => Presenters\Profile::class,
            'user' => Presenters\User::class,
        ]);

        assert(is_array($presenters));

        return $presenters;
    }
}
