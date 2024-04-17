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
                'status' => Filters\Status::class,
            ];
        }
    };
});

describe('Status Filter', function (): void {
    test('Status::handle modifies the query to filter by status by published',
        function (): void {
            $request = new Request();
            $request->merge(['filters' => ['status' => 'published']]);
            app()->instance('request', $request);

            $originalQuery = invokeNonPublicMethod($this->resourceController, 'getModelQuery');
            $filteredQuery = invokeNonPublicMethod($this->resourceController, 'applyFilters', [clone $originalQuery]);

            expect($filteredQuery->toSql())->toContain('"status" = ?')
                ->and($filteredQuery->getBindings())->toContain('published');
        });

    test('Status::handle modifies the query to filter by status by draft',
        function (): void {
            $request = new Request();
            $request->merge(['filters' => ['status' => 'draft']]);
            app()->instance('request', $request);

            $originalQuery = invokeNonPublicMethod($this->resourceController, 'getModelQuery');
            $filteredQuery = invokeNonPublicMethod($this->resourceController, 'applyFilters', [clone $originalQuery]);

            expect($filteredQuery->toSql())->toContain('"status" = ?')
                ->and($filteredQuery->getBindings())->toContain('draft');
        });

    test('Status::handle does not modify the query if status filter is not present in the request',
        function (): void {
            $request = new Request();
            app()->instance('request', $request);

            $originalQuery = invokeNonPublicMethod($this->resourceController, 'getModelQuery');
            $filteredQuery = invokeNonPublicMethod($this->resourceController, 'applyFilters', [clone $originalQuery]);

            expect($filteredQuery->toSql())->not()->toContain('"status" = ?');
        });
});
