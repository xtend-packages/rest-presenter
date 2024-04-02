<?php

namespace XtendPackages\RESTPresenter\Concerns;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait InteractsWithDbSchema
{
    protected function getAllTableNames(): Collection
    {
        return collect(DB::getSchemaBuilder()->getTables())->pluck('name');
    }

    protected function getTableColumns(string $table): Collection
    {
        return collect(Schema::getColumnListing($table));
    }

    protected function getTableColumnsForRelation(string $table, array $exclude = []): Collection
    {
        return $this->getTableColumns($table)->filter(
            fn (string $column) => ! in_array($column, $exclude),
        );
    }

    protected function findTableByName(string $table, $exactMatch = true): ?string
    {
        return $this->getAllTableNames()
            ->first(
                fn (string $tableName) => ! $exactMatch
                    ? Str::endsWith($tableName, $table)
                    : $tableName === $table,
            );
    }
}
