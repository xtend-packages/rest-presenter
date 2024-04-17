<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Lunar\FieldTypes\Text;
use Lunar\Models\Collection;
use Lunar\Models\Language;
use Lunar\Models\Url;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Data\Response\MediaData;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Collections\Presenters\Category\Category;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Collections\Presenters\Category\Data\CategoryData;

beforeEach(function (): void {
    $this->request = mock(Request::class);
    $this->language = mock(Language::class);
    $this->url = mock(Url::class);
    $this->collection = mock(Collection::class)
        ->makePartial()
        ->forceFill([
            'id' => 1,
            'attribute_data' => collect([
                'name' => new Text('Test Name'),
                'sub_heading' => new Text('Test Sub Heading'),
                'description' => new Text('Test Description'),
            ]),
            'urls' => collect([
                $this->url,
            ]),
        ]);

    $this->category = mock(
        Category::class,
        [$this->request, $this->collection]
    )->makePartial();

    $this->category
        ->shouldAllowMockingProtectedMethods()
        ->shouldReceive('transform')
        ->andReturn(CategoryData::from([
            'id' => 1,
            'slug' => 'test-slug',
            'name' => 'Test Name',
            'description' => 'Test Description',
            'banner' => fakeMediaItem(1),
        ]));
});

describe('Category Presenter', function (): void {
    test('Category::transform returns the correct data', function (): void {

        $result = $this->category->transform();

        expect($result)->toBeInstanceOf(CategoryData::class)
            ->and($result->id)->toBe(1)
            ->and($result->slug)->toBe('test-slug')
            ->and($result->name)->toBe('Test Name')
            ->and($result->description)->toBe('Test Description')
            ->and($result->banner)->toBeInstanceOf(MediaData::class);
    });
});
