<?php

use Illuminate\Http\Request;
use Lunar\Models\Product;
use XtendPackages\RESTPresenter\Concerns\InteractsWithModel;
use XtendPackages\RESTPresenter\Concerns\WithResourceFiltering;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Filters;

beforeEach(function (): void {
    $this->resourceController = new class() {
        use InteractsWithModel;
        use WithResourceFiltering;

        public function __construct()
        {
            static::$model = Product::class;
        }

        public function filters(): array
        {
            return [
                'brand_id' => Filters\Brand::class,
            ];
        }
    };
});

describe('Brand Filter', function (): void {
    test('Brand::handle modifies the query to filter by brand_id',
        function (): void {
            $request = new Request();
            $request->merge(['filters' => ['brand_id' => 1]]);
            app()->instance('request', $request);

            $originalQuery = invokeNonPublicMethod($this->resourceController, 'getModelQuery');
            $filteredQuery = invokeNonPublicMethod($this->resourceController, 'applyFilters', [clone $originalQuery]);

            expect($filteredQuery->toSql())->toContain('"brand_id" = ?')
                ->and($filteredQuery->getBindings())->toContain(1);
        });

    test('Brand::handle does not modify the query if brand_id is not present in the request',
        function (): void {
            $request = new Request();
            app()->instance('request', $request);

            $originalQuery = invokeNonPublicMethod($this->resourceController, 'getModelQuery');
            $filteredQuery = invokeNonPublicMethod($this->resourceController, 'applyFilters', [clone $originalQuery]);

            expect($filteredQuery->toSql())->not()->toContain('"brand_id" = ?');
        });
});
