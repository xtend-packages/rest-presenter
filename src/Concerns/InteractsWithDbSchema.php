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

    protected function getTableColumns(string $table, bool $withProperties = false): Collection
    {
        $columns = collect(! $withProperties
            ? Schema::getColumnListing($table)
            : Schema::getColumns($table),
        );

        if (DB::connection()->getDriverName() === 'sqlite' && $withProperties) {
            $columns = $this->replaceJsonColumnsSqliteWorkaround($table);
        }

        return $columns;
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

    private function replaceJsonColumnsSqliteWorkaround(string $table): Collection
    {
        $results = DB::select("PRAGMA table_info({$table})");

        return collect($results)->map(function ($column) use ($table) {
            $column_value = DB::table($table)->whereNotNull($column->name)->value($column->name);
            if (Str::isJson($column_value)) {
                $column->type_name = $column->type = 'json';
            }

            return (array) $column;
        });
    }
}
