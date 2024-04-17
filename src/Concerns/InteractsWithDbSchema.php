<?php

namespace XtendPackages\RESTPresenter\Concerns;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait InteractsWithDbSchema
{
    /**
     * @return Collection<(int|string), mixed>
     */
    protected function getAllTableNames(): Collection
    {
        return collect(DB::getSchemaBuilder()->getTables())->pluck('name');
    }

    /**
     * @return \Illuminate\Support\Collection<string, string>
     */
    protected function getTableColumns(string $table, bool $withProperties = false): Collection
    {
        if (DB::connection()->getDriverName() === 'sqlite' && $withProperties) {
            return $this->replaceJsonColumnsSqliteWorkaround($table);
        }
        return collect($withProperties
            ? Schema::getColumns($table)
            : Schema::getColumnListing($table),
        );
    }

    /**
     * @param  array<string>  $exclude
     * @return \Illuminate\Support\Collection<string, string>
     */
    protected function getTableColumnsForRelation(string $table, array $exclude = []): Collection
    {
        return $this->getTableColumns($table)->filter(
            fn (string $column): bool => ! in_array($column, $exclude),
        );
    }

    protected function findTableByName(string $table, bool $exactMatch = true): mixed
    {
        return $this->getAllTableNames()
            ->first(
                fn ($tableName) => $exactMatch
                    ? $tableName === $table
                    : Str::endsWith(type($tableName)->asString(), $table),
            );
    }

    /**
     * @param  array<string, mixed>  $fieldProperties
     */
    protected function isFieldNullable(array $fieldProperties): bool
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            return $fieldProperties['notnull'] === 0;
        }

        return type($fieldProperties['nullable'])->asBool();
    }

    /**
     * @return Collection<int|string, array<string, mixed>>
     */
    private function replaceJsonColumnsSqliteWorkaround(string $table): Collection
    {
        $results = DB::select("PRAGMA table_info({$table})");

        return collect($results)->map(function ($column) use ($table) {
            $column_value = DB::table($table)->whereNotNull($column->name)->value($column->name);
            if (Str::isJson($column_value)) {
                $column->type_name = $column->type = 'json';
            }

            return $column;
        });
    }
}
