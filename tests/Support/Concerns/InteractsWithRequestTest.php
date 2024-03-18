<?php

use Illuminate\Http\Request;
use XtendPackages\RESTPresenter\Concerns\InteractsWithRequest;

beforeEach(function () {
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

describe('InteractsWithRequest', function () {
    test('filterBy returns correct value', function () {
        $this->assertEquals('filterValue', $this->resourceController->filterBy('filterKey'));
        $this->assertNull($this->resourceController->filterBy('NotExistingKey'));
    });

    test('hasFilter returns correct value', function () {
        $this->assertTrue($this->resourceController->hasFilter('filterKey'));
        $this->assertFalse($this->resourceController->hasFilter('NotExistingKey'));
    });

    test('filtersFromRequest checks request for filters', function () {
        $this->assertEquals(['filterKey' => 'filterValue'], invokeNonPublicMethod($this->resourceController, 'filtersFromRequest'));
    });
});
