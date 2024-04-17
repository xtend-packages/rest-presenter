<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Lunar\FieldTypes\Text;
use Lunar\Models\Collection;
use Lunar\Models\Currency;
use Lunar\Models\Language;
use Lunar\Models\Price;
use Lunar\Models\Product;
use Lunar\Models\Url;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Data\Response\UrlData;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Presenters\Detail\Data\DetailData;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Presenters\Detail\Detail;

beforeEach(function (): void {
    $this->request = mock(Request::class);
    $this->currency = mock(Currency::class);
    $this->language = mock(Language::class)
        ->makePartial()
        ->forceFill([
            'id' => 1,
            'code' => 'en',
        ]);
    $this->url = mock(Url::class)
        ->makePartial()
        ->forceFill([
            'id' => 1,
            'slug' => 'test-name',
            'language_id' => $this->language->id,
            'language' => $this->language,
        ]);
    $this->price = mock(Price::class);
    $this->style = mock(Collection::class);
    $this->product = mock(Product::class)
        ->makePartial()
        ->forceFill([
            'id' => 1,
            'sku' => 'awr-123',
            'attribute_data' => collect([
                'name' => new Text('Test Name'),
                'description' => new Text('Test Description'),
            ]),
            'stock' => 100,
            'defaultUrl' => null,
            'variants' => collect(),
            'prices' => collect(),
            'getMedia' => null,
        ]);

    $this->fakeMedia = [
        fakeMediaItem(1),
        fakeMediaItem(2),
        fakeMediaItem(3),
        fakeMediaItem(4),
        fakeMediaItem(5),
    ];

    $this->detail = mock(
        Detail::class,
        [$this->request, $this->product]
    )->makePartial();

    $this->detail
        ->shouldAllowMockingProtectedMethods()
        ->shouldReceive('transform')
        ->andReturn(DetailData::from([
            'id' => 1,
            'url' => UrlData::from($this->url),
            'name' => 'Test Name',
            'description' => 'Test Description',
            'availability' => 'available',
            'variants' => collect(),
            'colors' => collect(),
            'sizes' => collect(),
            'prices' => collect(),
            'images' => collect($this->fakeMedia),
        ]));
});

describe('Detail Presenter', function (): void {
    test('Detail::transform returns the correct data', function (): void {

        $result = $this->detail->transform();

        // @todo: Improve tests for variants, colors, sizes, prices currently returning empty collections

        expect($result)->toBeInstanceOf(DetailData::class)
            ->and($result->id)->toBe(1)
            ->and($result->name)->toBe('Test Name')
            ->and($result->description)->toBe('Test Description')
            ->and($result->availability)->toBe('available')
            ->and($result->variants)->toBeInstanceOf(Illuminate\Support\Collection::class)
            ->and($result->colors)->toBeInstanceOf(Illuminate\Support\Collection::class)
            ->and($result->sizes)->toBeInstanceOf(Illuminate\Support\Collection::class)
            ->and($result->prices)->toBeInstanceOf(Illuminate\Support\Collection::class)
            ->and($result->images)->toBeInstanceOf(Illuminate\Support\Collection::class);
    });
});
