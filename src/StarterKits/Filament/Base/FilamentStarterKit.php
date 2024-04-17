<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Filament\Base;

use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\Finder\SplFileInfo;
use XtendPackages\RESTPresenter\Base\StarterKit;
use XtendPackages\RESTPresenter\Concerns\InteractsWithDbSchema;

final class FilamentStarterKit extends StarterKit
{
    use InteractsWithDbSchema;

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
        if (! $this->filesystem->isDirectory(app_path('Filament/Resources'))) {
            return;
        }

        collect($this->filesystem->allFiles(app_path('Filament/Resources')))
            ->filter(fn (SplFileInfo $file): bool => basename($file->getRelativePath()) === 'Pages')
            ->map(fn (SplFileInfo $file): string => $file->getRelativePathname())
            ->map(fn (string $file) => resolve('App\\Filament\\Resources\\'.str_replace(['/', '.php'], ['\\', ''], $file)))
            ->filter(fn ($class): bool => is_subclass_of($class, ListRecords::class))
            ->each(function ($page): void {
                /** @var ListRecords $page */
                $page = type($page)->as(ListRecords::class);
                /** @var \Filament\Tables\Table $table */
                $table = $page->table(
                    table: mock(\Filament\Tables\Table::class)->makePartial(),
                );

                $resourceNamespace = Str::of($page::class)
                    ->replace('App\\Filament\\', '')
                    ->before('\\Pages')
                    ->replaceLast('Resource', '')
                    ->plural()
                    ->value();

                // $fields = collect($form->getComponents())
                //     ->filter(fn ($field) => is_subclass_of($field, Field::class))
                //     ->map(fn ($field) => $field->getName());
                // @todo Merge validation rules from nested form fields

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
     * @return \Illuminate\Support\Collection<int|string, array<string, mixed>>
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
