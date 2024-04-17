<?php

use Illuminate\Http\Request;
use Lunar\Models\Collection;
use XtendPackages\RESTPresenter\Concerns\InteractsWithModel;
use XtendPackages\RESTPresenter\Concerns\WithResourceFiltering;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Collections\Filters;

beforeEach(function (): void {
    $this->resourceController = new class() {
        use InteractsWithModel;
        use WithResourceFiltering;

        public function __construct()
        {
            static::$model = Collection::class;
        }

        public function filters(): array
        {
            return [
                'collection_group' => Filters\CollectionGroup::class,
            ];
        }
    };
});

describe('CollectionGroup Filter', function (): void {
    test('CollectionGroup::handle modifies the query if collection_group_id filter is present in the request',
        function (): void {
            $request = new Request();
            $request->merge(['filters' => ['collection_group_id' => 1]]);
            app()->instance('request', $request);

            $originalQuery = invokeNonPublicMethod($this->resourceController, 'getModelQuery');
            $filteredQuery = invokeNonPublicMethod($this->resourceController, 'applyFilters', [clone $originalQuery]);

            expect($filteredQuery->toSql())->toContain('"collection_group_id" = ?')
                ->and($filteredQuery->getBindings())->toContain(1);
        });

    test('CollectionGroup::handle does not modify the query if collection_group_id filter is not present in the request',
        function (): void {
            $request = new Request();
            app()->instance('request', $request);

            $originalQuery = invokeNonPublicMethod($this->resourceController, 'getModelQuery');
            $filteredQuery = invokeNonPublicMethod($this->resourceController, 'applyFilters', [clone $originalQuery]);

            expect($filteredQuery->toSql())->not()->toContain('"collection_group_id" = ?');
        });
});
