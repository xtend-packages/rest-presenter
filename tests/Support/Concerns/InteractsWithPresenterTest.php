<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Spatie\LaravelData\Data;
use XtendPackages\RESTPresenter\Concerns\InteractsWithPresenter;
use XtendPackages\RESTPresenter\Exceptions\PresenterNotFoundException;
use XtendPackages\RESTPresenter\Resources\Users\Presenters;
use XtendPackages\RESTPresenter\Support\ResourceDefaultPresenter;

beforeEach(function (): void {
    $this->resourceController = new class {
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

        $presenter = invokeNonPublicMethod(
            object: $this->resourceController,
            methodName: 'makePresenter',
            parameters: [$request, $model],
        );

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

        // @todo Simplify Mocking API

        $result = invokeNonPublicMethod(
            object: $this->resourceController,
            methodName: 'present',
            parameters: [$request, $model],
        );

        expect($result)->toBeInstanceOf(Data::class);
    });

    test('getPresenters merges default and resource presenters', function (): void {
        $presenters = invokeNonPublicMethod(
            object: $this->resourceController,
            methodName: 'getPresenters',
        );

        expect($presenters)->toBeArray()
            ->and($presenters['default'])
            ->toBe(ResourceDefaultPresenter::class);
    });

    test('getPresenterFromRequestHeader returns correct presenter from user request header is set', function (): void {
        request()->headers->set(getApiHeaderPresenterName(), 'user');

        $presenter = invokeNonPublicMethod(
            object: $this->resourceController,
            methodName: 'getPresenterFromRequestHeader',
        );

        expect($presenter)->toBe(Presenters\User::class);
    });

    test('getPresenterFromRequestHeader throws PresenterNotFoundException when presenter key is not found', function (): void {
        request()->headers->set(getApiHeaderPresenterName(), 'NonExistentPresenter');

        $this->expectException(PresenterNotFoundException::class);

        invokeNonPublicMethod(
            object: $this->resourceController,
            methodName: 'getPresenterFromRequestHeader',
        );
    });

    test('getPresenterFromRequestHeader returns default presenter when request header is not set', function (): void {
        request()->headers->remove(getApiHeaderPresenterName());

        $presenter = invokeNonPublicMethod(
            object: $this->resourceController,
            methodName: 'getPresenterFromRequestHeader',
        );

        expect($presenter)->toBe(ResourceDefaultPresenter::class);
    });
});
