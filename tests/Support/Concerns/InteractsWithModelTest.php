<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use XtendPackages\RESTPresenter\Concerns\InteractsWithModel;

use function XtendPackages\RESTPresenter\Support\Tests\invokeNonPublicMethod;

test('should return correct model modified query instance', function (): void {
    $mock = new class
    {
        use InteractsWithModel;

        public function __construct()
        {
            self::$model = (new class extends Model
            {
            })::class;
        }
    };

    $query = invokeNonPublicMethod($mock, 'getModelQuery');
    expect($query)->toBeInstanceOf(Builder::class);

    $queryModified = $query->where('column', 'value');
    invokeNonPublicMethod($mock, 'setModelQuery', [$queryModified]);

    /** @var Builder $instance */
    $instance = invokeNonPublicMethod($mock, 'getModelQueryInstance');
    expect($instance->toSql())->toBe($queryModified->toSql())
        ->and($instance)->toBeInstanceOf(Builder::class);

});
