<?php

declare(strict_types=1);

use XtendPackages\RESTPresenter\Concerns\WithResourceRouteActions;

beforeEach(function (): void {
    $this->classInstance = new class
    {
        use WithResourceRouteActions;

        public static function routeActions(): array
        {
            return ['index' => 'getIndex', 'store' => 'storeRecord'];
        }
    };
});

it('can get route actions where they exist', function (): void {
    $actions = invade($this->classInstance)->getRouteActions();

    $this->assertEquals(['index' => 'getIndex', 'store' => 'storeRecord'], $actions);
});

it('returns an empty array when routeActions method does not exist', function (): void {
    $this->classInstance = new class
    {
        use WithResourceRouteActions;
    };

    $actions = invade($this->classInstance)->getRouteActions();

    $this->assertEquals([], $actions);
});
