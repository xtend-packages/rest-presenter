<?php

namespace XtendPackages\RESTPresenter\StarterKits\Filament\Base;

use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Field;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Symfony\Component\Finder\SplFileInfo;
use XtendPackages\RESTPresenter\Base\StarterKit;
use XtendPackages\RESTPresenter\Concerns\InteractsWithDbSchema;

class FilamentStarterKit extends StarterKit
{
    use InteractsWithDbSchema;

    protected array $resources = [];

    public function autoDiscover(): array
    {
        $this->autoDiscoverResources();

        return $this->resources;
    }

    protected function autoDiscoverResources(): void
    {
        collect($this->filesystem->allFiles(app_path('Filament/Resources')))
            ->filter(fn (SplFileInfo $file) => basename($file->getRelativePath()) === 'Pages')
            ->map(fn (SplFileInfo $file) => $file->getRelativePathname())
            ->map(fn (string $file) => resolve('App\\Filament\\Resources\\' . str_replace(['/', '.php'], ['\\', ''], $file)))
            ->filter(fn ($class) => is_subclass_of($class, ListRecords::class))
            ->each(function (ListRecords $page) {
                /** @var \Filament\Tables\Table $table */
                $table = $page->table(
                    table: mock('Filament\Tables\Table')->makePartial(),
                );

                /** @var \Filament\Forms\Form $form */
                // $form = $page->form(
                //     form: mock('Filament\Forms\Form')->makePartial(),
                // );

                $resourceNamespace = Str::of(get_class($page))
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
                    fn ($column) => [$column => $modelFields[$column]],
                )->merge($modelFields);

                $this->resources[$resourceNamespace] = [
                    'columns' => collect($table->getColumns())->keys(),
                    'fields' => $fields->toArray(),
                    'model' => $page->getModel(),
                ];
            });
    }

    protected function generateModelFields(Model $model): Collection
    {
        $table = $model->getTable();

        return $this->getTableColumns(
            table: $table,
            withProperties: true,
        );
    }
}
