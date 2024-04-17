<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Lunar\Models\Brand;
use Lunar\Models\Language;
use Lunar\Models\Url;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Data\Response\MediaData;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Brands\Presenters\Brand\Brand as BrandPresenter;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Brands\Presenters\Brand\Data\BrandData;

beforeEach(function (): void {
    $this->request = mock(Request::class);
    $this->language = mock(Language::class);
    $this->url = mock(Url::class);
    $this->brandModel = mock(Brand::class)
        ->makePartial()
        ->forceFill([
            'id' => 1,
            'name' => 'New Brand',
            'urls' => collect([
                $this->url,
            ]),
        ]);

    $this->brand = mock(
        BrandPresenter::class,
        [$this->request, $this->brandModel]
    )->makePartial();

    $this->brand
        ->shouldAllowMockingProtectedMethods()
        ->shouldReceive('transform')
        ->andReturn(BrandData::from([
            'id' => 1,
            'slug' => 'new-brand',
            'name' => 'New Brand',
            'image' => fakeMediaItem(1),
        ]));
});

describe('Brand Presenter', function (): void {
    test('Brand::transform returns the correct data', function (): void {

        $result = $this->brand->transform();

        expect($result)->toBeInstanceOf(BrandData::class)
            ->and($result->id)->toBe(1)
            ->and($result->slug)->toBe('new-brand')
            ->and($result->name)->toBe('New Brand')
            ->and($result->image)->toBeInstanceOf(MediaData::class);
    });
});
