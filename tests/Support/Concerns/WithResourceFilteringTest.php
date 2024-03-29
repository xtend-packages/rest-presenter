<?php

use Illuminate\Http\Request;
use XtendPackages\RESTPresenter\Concerns\InteractsWithModel;
use XtendPackages\RESTPresenter\Concerns\WithResourceFiltering;
use XtendPackages\RESTPresenter\Models\User;
use XtendPackages\RESTPresenter\Resources\Users\Filters\UserEmailVerified;

beforeEach(function () {
    $this->resourceController = new class() {
        use InteractsWithModel;
        use WithResourceFiltering;

        public function __construct()
        {
            static::$model = User::class;
        }

        public function filters(): array
        {
            return [
                'email_verified_at' => UserEmailVerified::class,
            ];
        }
    };
});

describe('WithResourceFiltering', function () {
    test('applyFilters correctly modifies the query', function () {
        $request = new Request();
        $request->merge(['filters' => ['email_verified_at' => now()]]);
        app()->instance('request', $request);

        $originalQuery = invokeNonPublicMethod($this->resourceController, 'getModelQuery');
        $newQuery = invokeNonPublicMethod($this->resourceController, 'applyFilters', [clone $originalQuery]);

        expect($newQuery->toSql())->toContain('"email_verified_at" is not null')
            ->and($newQuery->toSql())->not()->toBe($originalQuery->toSql());
    });

    test('getFilters returns resource filters', function () {
        $filters = invokeNonPublicMethod($this->resourceController, 'getFilters');

        expect($filters)->toBeArray()
            ->and($filters)->toHaveKey('email_verified_at');
    });
});
