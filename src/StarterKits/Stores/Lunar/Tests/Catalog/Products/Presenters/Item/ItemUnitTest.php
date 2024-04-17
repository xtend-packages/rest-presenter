<?php

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Lunar\FieldTypes\Text;
use Lunar\Models\Currency;
use Lunar\Models\Language;
use Lunar\Models\Price;
use Lunar\Models\Product;
use Lunar\Models\Url;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Data\Response\MediaData;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Data\Response\PriceData;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Data\Response\UrlData;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Presenters\Detail\Data\Variant\ColorData;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Presenters\Item\Data\ItemData;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Presenters\Item\Item;

beforeEach(function (): void {
    $this->request = mock(Request::class);
    $this->currency = mock(Currency::class)
        ->makePartial()
        ->forceFill([
            'id' => 1,
            'code' => 'USD',
        ]);
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

    $this->price = mock(Price::class)
        ->makePartial()
        ->forceFill([
            'id' => 1,
            'customer_group_id' => 1,
            'currency_id' => 1,
            'price' => new \Lunar\DataTypes\Price(100, $this->currency),
            'compare_price' => new \Lunar\DataTypes\Price(0, $this->currency),
            'tier' => 1,
        ]);
    $this->product = mock(Product::class)
        ->makePartial()
        ->forceFill([
            'id' => 1,
            'attribute_data' => collect([
                'name' => new Text('Test Name'),
                'description' => new Text('Test Description'),
            ]),
            'stock' => 100,
            'defaultUrl' => null,
            'variants' => collect(),
            'prices' => collect(
                $this->price,
            ),
            'getMedia' => null,
        ]);

    $this->item = mock(
        Item::class,
        [$this->request, $this->product]
    )->makePartial();

    $this->item
        ->shouldAllowMockingProtectedMethods()
        ->shouldReceive('transform')
        ->andReturn(ItemData::from([
            'id' => $this->product->id,
            'slug' => UrlData::from($this->url),
            'name' => $this->product->translateAttribute('name'),
            'availability' => 'available',
            'stock' => $this->product->stock,
            'colors' => ColorData::collect([]),
            'price' => PriceData::from($this->price),
            'image' => MediaData::from(fakeMediaItem(1)),
        ]));
});

describe('Item Presenter', function (): void {
    test('Item::transform returns the correct data', function (): void {

        $result = $this->item->transform();

        // @todo: Improve tests for colors currently returning empty collection

        expect($result)->toBeInstanceOf(ItemData::class)
            ->and($result->id)->toBe(1)
            ->and($result->slug)->toBeInstanceOf(UrlData::class)
            ->and($result->name)->toBe('Test Name')
            ->and($result->availability)->toBe('available')
            ->and($result->colors)->toBeInstanceOf(Collection::class)
            ->and($result->price)->toBeInstanceOf(PriceData::class)
            ->and($result->image)->toBeInstanceOf(MediaData::class);
    });
});
