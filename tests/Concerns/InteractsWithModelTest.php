<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use XtendPackages\RESTPresenter\Concerns\InteractsWithModel;

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

    $query = invade($mock)->getModelQuery();
    expect($query)->toBeInstanceOf(Builder::class);

    $queryModified = $query->where('column', 'value');
    invade($mock)->setModelQuery($queryModified);

    /** @var Builder $instance */
    $instance = invade($mock)->getModelQueryInstance();
    expect($instance->toSql())->toBe($queryModified->toSql())
        ->and($instance)->toBeInstanceOf(Builder::class);

});
