<?php

namespace XtendPackages\RESTPresenter\StarterKits\Filament\Base;

use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Str;
use Symfony\Component\Finder\SplFileInfo;
use XtendPackages\RESTPresenter\Base\StarterKit;

class FilamentStarterKit extends StarterKit
{
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
                $form = $page->form(
                    form: mock('Filament\Forms\Form')->makePartial(),
                );
                $resourceNamespace = Str::of(get_class($page))
                    ->replace('App\\Filament\\', '')
                    ->before('\\Pages')
                    ->replaceLast('Resource', '')
                    ->value();
                $this->resources[$resourceNamespace] = [
                    'columns' => collect($table->getColumns())->keys(),
                    'fields' => $form->getComponents(),
                    'model' => $page->getModel(),
                ];
            });
    }
}
