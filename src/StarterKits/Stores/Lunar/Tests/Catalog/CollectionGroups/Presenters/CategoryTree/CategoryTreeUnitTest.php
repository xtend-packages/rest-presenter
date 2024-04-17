<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Lunar\FieldTypes\Text;
use Lunar\Models\Collection;
use Lunar\Models\CollectionGroup;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\CollectionGroups\Presenters\CategoryTree\CategoryTree;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\CollectionGroups\Presenters\CategoryTree\Concerns\WithGenerateCollectionsTree;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\CollectionGroups\Presenters\CategoryTree\Data\CategoryData;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\CollectionGroups\Presenters\CategoryTree\Data\TreeData;

uses(WithGenerateCollectionsTree::class);

function mockCollection($id = null)
{
    return mock(Collection::class)
        ->makePartial()
        ->forceFill([
            'id' => $id,
            'attribute_data' => collect([
                'name' => new Text('Category '.$id),
            ]),
            'children' => collect(),
        ]);
}

beforeEach(function (): void {
    $this->request = mock(Request::class);
    $this->collectionGroup = mock(CollectionGroup::class)
        ->makePartial()
        ->forceFill([
            'id' => 1,
            'name' => 'Test Name',
        ]);

    $this->categoryTree = mock(
        CategoryTree::class,
        [$this->request, $this->collectionGroup]
    )->makePartial();

    $this->categoryTree
        ->shouldAllowMockingProtectedMethods()
        ->shouldReceive('transform')
        ->andReturn(TreeData::from(
            model: $this->collectionGroup,
            categories: $this->generateTree(collect([
                mockCollection(1),
                mockCollection(2),
                mockCollection(3),
                mockCollection(4),
                mockCollection(5),
            ])),
        ));
});

describe('CategoryTree Presenter', function (): void {
    test('CategoryTree::transform returns the correct data', function (): void {

        $result = $this->categoryTree->transform();

        // Test the response structure
        expect($result)->toBeInstanceOf(TreeData::class)
            ->and($result->id)->toBe(1)
            ->and($result->name)->toBe('Test Name')
            ->and($result->categories)->toBeInstanceOf(Illuminate\Support\Collection::class)
            ->and($result->categories)->toHaveCount(5);

        // Test the categories within nested children
        collect($result->categories)->each(function (CategoryData $category, $index): void {
            expect($category->id)->toBe($index + 1)
                ->and($category->name)->toBe('Category '.($index + 1))
                ->and($category->slug)->toBe(($index + 1).'-category-'.($index + 1))
                // @todo Add additional assertions for children later
                ->and($category->children)->toBeArray()
                ->and($category->children)->toHaveCount(0);
        });
    });
});
