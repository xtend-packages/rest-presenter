<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Concerns;

use Closure;
use DateTime;
use Illuminate\Database\Connectors\ConnectionFactory;
use Illuminate\Database\QueryException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;
use ReflectionClass;

trait InteractsWithSushi
{
    protected static mixed $sushiConnection;

    public static function resolveConnection($connection = null): mixed
    {
        return static::$sushiConnection;
    }

    public static function bootInteractsWithSushi(): void
    {
        $instance = (new static);

        $cachePath = $instance->sushiCachePath();
        $dataPath = $instance->sushiCacheReferencePath();

        $states = [
            'cache-file-found-and-up-to-date' => function () use ($cachePath): void {
                static::setSqliteConnection($cachePath);
            },
            'cache-file-not-found-or-stale' => function () use ($cachePath, $dataPath, $instance): void {
                static::cacheFileNotFoundOrStale($cachePath, $dataPath, $instance);
            },
            'no-caching-capabilities' => function () use ($instance): void {
                static::setSqliteConnection(':memory:');

                $instance->migrate();
            },
        ];

        match (true) {
            ! $instance->sushiShouldCache() => $states['no-caching-capabilities'](),
            file_exists($cachePath) && filemtime($dataPath) <= filemtime($cachePath) => $states['cache-file-found-and-up-to-date'](),
            file_exists($instance->sushiCacheDirectory()) && is_writable($instance->sushiCacheDirectory()) => $states['cache-file-not-found-or-stale'](),
            default => $states['no-caching-capabilities'](),
        };
    }

    public function getRows(): array
    {
        return $this->rows;
    }

    public function getSchema(): array
    {
        return $this->schema ?? [];
    }

    public function migrate(): void
    {
        $rows = $this->getRows();
        $tableName = $this->getTable();

        if (count($rows) > 0) {
            $this->createTable($tableName, $rows[0]);
        } else {
            $this->createTableWithNoData($tableName);
        }

        foreach (array_chunk($rows, $this->getSushiInsertChunkSize()) ?? [] as $inserts) {
            if ($inserts !== []) {
                static::insert($inserts);
            }
        }
    }

    public function createTable(string $tableName, $firstRow): void
    {
        $this->createTableSafely($tableName, function ($table) use ($firstRow): void {
            // Add the "id" column if it doesn't already exist in the rows.
            if ($this->incrementing && ! array_key_exists($this->primaryKey, $firstRow)) {
                $table->increments($this->primaryKey);
            }

            foreach ($firstRow as $column => $value) {

                $type = match (true) {
                    is_int($value) => 'integer',
                    is_numeric($value) => 'float',
                    is_string($value) => 'string',
                    $value instanceof DateTime => 'dateTime',
                    default => 'string',
                };

                if ($column === $this->primaryKey && $type === 'integer') {
                    $table->increments($this->primaryKey);

                    continue;
                }

                $schema = $this->getSchema();

                $type = $schema[$column] ?? $type;

                $table->{$type}($column)->nullable();
            }

            if ($this->usesTimestamps() && (! in_array('updated_at', array_keys($firstRow)) || ! in_array('created_at', array_keys($firstRow)))) {
                $table->timestamps();
            }

            $this->afterMigrate($table);
        });
    }

    public function createTableWithNoData(string $tableName): void
    {
        $this->createTableSafely($tableName, function ($table): void {
            $schema = $this->getSchema();

            if ($this->incrementing && ! in_array($this->primaryKey, array_keys($schema))) {
                $table->increments($this->primaryKey);
            }

            foreach ($schema as $name => $type) {
                if ($name === $this->primaryKey && $type === 'integer') {
                    $table->increments($this->primaryKey);

                    continue;
                }

                $table->{$type}($name)->nullable();
            }
            if (! $this->usesTimestamps()) {
                return;
            }
            if (in_array('updated_at', array_keys($schema)) && in_array('created_at', array_keys($schema))) {
                return;
            }
            $table->timestamps();
        });
    }

    public function usesTimestamps(): bool
    {
        // Override the Laravel default value of $timestamps = true; Unless otherwise set.
        return (new ReflectionClass($this))->getProperty('timestamps')->class === static::class && parent::usesTimestamps();
    }

    public function getSushiInsertChunkSize(): int
    {
        return $this->sushiInsertChunkSize ?? 100;
    }

    public function getConnectionName(): string
    {
        return static::class;
    }

    protected static function cacheFileNotFoundOrStale($cachePath, $dataPath, $instance): void
    {
        file_put_contents($cachePath, '');

        static::setSqliteConnection($cachePath);

        $instance->migrate();

        touch($cachePath, filemtime($dataPath));
    }

    protected static function setSqliteConnection($database): void
    {
        $config = [
            'driver' => 'sqlite',
            'database' => $database,
        ];

        static::$sushiConnection = app(ConnectionFactory::class)->make($config);

        app('config')->set('database.connections.'.static::class, $config);
    }

    protected function sushiCacheReferencePath(): string|false
    {
        return (new ReflectionClass(static::class))->getFileName();
    }

    protected function sushiShouldCache(): bool
    {
        return property_exists(static::class, 'rows');
    }

    protected function sushiCachePath(): string
    {
        return implode(DIRECTORY_SEPARATOR, [
            $this->sushiCacheDirectory(),
            $this->sushiCacheFileName(),
        ]);
    }

    protected function sushiCacheFileName(): string
    {
        return 'sushi'.'-'.Str::kebab(str_replace('\\', '', static::class)).'.sqlite';
    }

    protected function sushiCacheDirectory(): string
    {
        return realpath(storage_path('framework/cache'));
    }

    protected function newRelatedInstance($class): mixed
    {
        return tap(new $class, function ($instance): void {
            if (! $instance->getConnectionName()) {
                $instance->setConnection($this->getConnectionResolver()->getDefaultConnection());
            }
        });
    }

    protected function afterMigrate(Blueprint $table): void
    {
        //
    }

    protected function createTableSafely(string $tableName, Closure $callback): void
    {
        /** @var \Illuminate\Database\Schema\SQLiteBuilder $schemaBuilder */
        $schemaBuilder = static::resolveConnection()->getSchemaBuilder();

        try {
            $schemaBuilder->create($tableName, $callback);
        } catch (QueryException $e) {
            if (Str::contains($e->getMessage(), [
                'already exists (SQL: create table',
                sprintf('table "%s" already exists', $tableName),
            ])) {
                // This error can happen in rare circumstances due to a race condition.
                // Concurrent requests may both see the necessary preconditions for
                // the table creation, but only one can actually succeed.
                return;
            }

            throw $e;
        }
    }
}
