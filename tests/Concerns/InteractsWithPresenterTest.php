<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Spatie\LaravelData\Data;
use XtendPackages\RESTPresenter\Concerns\InteractsWithPresenter;
use XtendPackages\RESTPresenter\Exceptions\PresenterNotFoundException;
use XtendPackages\RESTPresenter\Resources\Users\Presenters;
use XtendPackages\RESTPresenter\Support\ResourceDefaultPresenter;

use function XtendPackages\RESTPresenter\Support\Tests\getApiHeaderPresenterName;

beforeEach(function (): void {
    $this->resourceController = new class
    {
        use InteractsWithPresenter;

        public function presenters(): array
        {
            return [
                'default' => ResourceDefaultPresenter::class,
                'user' => Presenters\User::class,
            ];
        }
    };
});

describe('InteractsWithPresenter', function (): void {
    test('makePresenter returns Presentable', function (): void {
        $request = mock(Request::class);
        $model = mock(Model::class);

        $presenter = invade($this->resourceController)->makePresenter($request, $model);

        expect($presenter)->toBeInstanceOf(ResourceDefaultPresenter::class);
    });

    test('present returns correct format', function (): void {
        $request = mock(Request::class);
        $model = Mockery::spy(Model::class);
        $model->shouldReceive('toArray');

        $model
            ->shouldReceive('getAttribute')
            ->with('id')
            ->andReturn(1);

        $model
            ->shouldReceive('getAttribute')
            ->with('name')
            ->andReturn('Test Name');

        $model
            ->shouldReceive('getAttribute')
            ->with('created_at')
            ->andReturn(now());

        $model
            ->shouldReceive('getAttribute')
            ->with('updated_at')
            ->andReturn(now());

        $model
            ->shouldReceive('getAttributes')
            ->andReturn([
                'id' => 1,
                'name' => 'Test Name',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        $result = invade($this->resourceController)->present($request, $model);

        expect($result)->toBeInstanceOf(Data::class);
    });

    test('getPresenters merges default and resource presenters', function (): void {
        $presenters = invade($this->resourceController)->getPresenters();

        expect($presenters)->toBeArray()
            ->and($presenters['default'])
            ->toBe(ResourceDefaultPresenter::class);
    });

    test('getPresenterFromRequestHeader returns correct presenter from user request header is set', function (): void {
        request()->headers->set(getApiHeaderPresenterName(), 'user');

        $presenter = invade($this->resourceController)->getPresenterFromRequestHeader();

        expect($presenter)->toBe(Presenters\User::class);
    });

    test('getPresenterFromRequestHeader throws PresenterNotFoundException when presenter key is not found', function (): void {
        request()->headers->set(getApiHeaderPresenterName(), 'NonExistentPresenter');

        $this->expectException(PresenterNotFoundException::class);

        invade($this->resourceController)->getPresenterFromRequestHeader();
    });

    test('getPresenterFromRequestHeader returns default presenter when request header is not set', function (): void {
        request()->headers->remove(getApiHeaderPresenterName());

        $presenter = invade($this->resourceController)->getPresenterFromRequestHeader();

        expect($presenter)->toBe(ResourceDefaultPresenter::class);
    });
});
