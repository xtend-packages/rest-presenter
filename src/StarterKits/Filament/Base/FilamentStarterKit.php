<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Filament\Base;

use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use XtendPackages\RESTPresenter\Base\StarterKit;
use XtendPackages\RESTPresenter\Concerns\InteractsWithClassDefinitions;
use XtendPackages\RESTPresenter\Concerns\InteractsWithDbSchema;

final class FilamentStarterKit extends StarterKit
{
    use InteractsWithClassDefinitions;
    use InteractsWithDbSchema;

    protected static string $packageName = 'filament/filament';

    /**
     * @var array<string, array<string, mixed>>
     */
    private array $resources = [];

    /**
     * @return array<string, array<string, mixed>>
     */
    public function autoDiscover(): array
    {
        $this->autoDiscoverResources();

        return $this->resources;
    }

    private function autoDiscoverResources(): void
    {
        if (! self::$packageInstalled) {
            return;
        }

        $definitions = $this->scanClassDefinitions(
            filenamePrefix: 'List',
            removeFromGroupKey: 'Pages',
            parentClass: ListRecords::class,
        )->collapse();

        $definitions
            // @phpstan-ignore-next-line
            ->each(function (string $class): void {
                $page = resolve($class);
                /** @var ListRecords $page */
                $page = type($page)->as(ListRecords::class);
                $table = $page->table(
                    table: mock(Table::class)->makePartial(), // @phpstan-ignore-line
                );

                $resourceNamespace = Str::of($page::class)
                    ->replace('App\\Filament\\', '')
                    ->before('\\Pages')
                    ->replaceLast('Resource', '')
                    ->plural()
                    ->value();

                $modelFields = $this->generateModelFields(resolve($page->getModel()))->keyBy('name');
                $tableColumns = collect(['id' => 'integer'])->merge(collect($table->getColumns()))->keys()->values();
                $fields = $tableColumns->intersect($modelFields->keys())->mapWithKeys(
                    fn ($column): array => [$column => $modelFields[$column]],
                )->merge($modelFields);

                $this->resources[$resourceNamespace] = [
                    'columns' => collect($table->getColumns())->keys(),
                    'fields' => $fields->toArray(),
                    'model' => $page->getModel(),
                ];
            });
    }

    /**
     * @phpstan-ignore-next-line
     */
    private function generateModelFields(Model $model): Collection
    {
        $table = $model->getTable();

        return $this->getTableColumns(
            table: $table,
            withProperties: true,
        );
    }
}
