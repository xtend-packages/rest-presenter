<?php

use Illuminate\Http\Request;
use XtendPackages\RESTPresenter\Concerns\InteractsWithRequest;

beforeEach(function (): void {
    $request = new Request();
    $request->merge(['filters' => ['filterKey' => 'filterValue']]);
    app()->instance('request', $request);

    $this->resourceController = new class {
        use InteractsWithRequest;

        public function filters(): array
        {
            return ['filterKey' => 'filterValue'];
        }
    };
});

describe('InteractsWithRequest', function (): void {
    test('filterBy returns correct value', function (): void {
        $this->assertEquals('filterValue', $this->resourceController->filterBy('filterKey'));
        $this->assertNull($this->resourceController->filterBy('NotExistingKey'));
    });

    test('hasFilter returns correct value', function (): void {
        $this->assertTrue($this->resourceController->hasFilter('filterKey'));
        $this->assertFalse($this->resourceController->hasFilter('NotExistingKey'));
    });

    test('filtersFromRequest checks request for filters', function (): void {
        $this->assertEquals(['filterKey' => 'filterValue'], invokeNonPublicMethod($this->resourceController, 'filtersFromRequest'));
    });
});
