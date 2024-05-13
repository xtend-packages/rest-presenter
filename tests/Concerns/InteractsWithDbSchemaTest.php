<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use XtendPackages\RESTPresenter\Concerns\InteractsWithDbSchema;

beforeEach(function (): void {
    $this->classInstance = new class
    {
        use InteractsWithDbSchema;
    };

    config(['database.default' => 'sqlite']);
    config(['database.connections.sqlite' => [
        'driver' => 'sqlite',
        'database' => ':memory:',
    ]]);

    DB::beginTransaction();

    Schema::create('users', function (Blueprint $table): void {
        $table->id();
        $table->string('name');
        $table->integer('status')->default(0);
    });
});

afterEach(function (): void {
    DB::rollBack();
});

test('can get all table names', function (): void {
    $results = invade($this->classInstance)->getAllTableNames();

    $this->assertEquals('users', $results->first());
});

test('can get table columns', function (): void {
    $results = invade($this->classInstance)->getTableColumns('users', false);

    $this->assertEquals(['id', 'name', 'status'], $results->all());
});

test('can find table by name', function (): void {
    $result = invade($this->classInstance)->findTableByName('users', true);

    $this->assertEquals('users', $result);
});
