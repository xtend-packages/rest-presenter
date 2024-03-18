<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use XtendPackages\RESTPresenter\Concerns\InteractsWithModel;

test('should return correct model modified query instance', function () {
    $mock = new class {
        use InteractsWithModel;

        public function __construct()
        {
            static::$model = get_class(new class extends Model{});
        }
    };

    $query = invokeNonPublicMethod($mock, 'getModelQuery');
    expect($query)->toBeInstanceOf(Builder::class);

    /** @var Builder $instance */
    $queryModified = $query->where('column', 'value');
    invokeNonPublicMethod($mock, 'setModelQuery', [$queryModified]);

    /** @var Builder $instance */
    $instance = invokeNonPublicMethod($mock, 'getModelQueryInstance');
    expect($instance->toSql())->toBe($queryModified->toSql())
        ->and($instance)->toBeInstanceOf(Builder::class);

});
