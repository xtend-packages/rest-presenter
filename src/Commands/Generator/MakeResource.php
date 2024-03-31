<?php

namespace XtendPackages\RESTPresenter\Commands\Generator;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\SplFileInfo;
use XtendPackages\RESTPresenter\Concerns\InteractsWithDbSchema;

use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\search;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

#[AsCommand(name: 'rest-presenter:make-resource')]
class MakeResource extends GeneratorCommand
{
    use InteractsWithDbSchema;

    protected $name = 'rest-presenter:make-resource';

    protected $description = 'Create a new resource class';

    protected $type = 'Resource';

    protected Model $model;

    protected Collection $filters;

    protected Collection $presenters;

    public function handle()
    {
        $this->filters->each(
            fn (string $filter, string $relation) => $this->call(
                command: 'rest-presenter:make-filter',
                arguments: [
                    'name' => ucfirst($filter),
                    'resource' => Str::plural($this->argument('name')),
                    'relation' => Str::of($relation)->after('=>')->value(),
                    'relation_search_key' => $this->guessRelationSearchKey($filter),
                    'type' => 'new',
                ],
            ),
        );

        $this->presenters->each(
            fn (array $fields, string $presenter) => $this->call(
                command: 'rest-presenter:make-presenter',
                arguments: [
                    'name' => $presenter,
                    'type' => 'new',
                    'model' => $this->argument('model'),
                    'resource' => Str::plural($this->argument('name')),
                    'fields' => $fields,
                ],
            ),
        );

        parent::handle();
    }

    protected function guessRelationSearchKey(string $filter): ?string
    {
        if (! $relationTable = $this->findTableByName(table: $filter, exactMatch: false)) {
            return null;
        }

        return $this->getTableColumnsForRelation(
            table: $relationTable,
            exclude: ['id', 'created_at', 'updated_at'],
        )->keys()->first();
    }

    protected function getStub(): string
    {
        return __DIR__ . '/stubs/' . $this->argument('type') . '/resource.controller.php.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        $resourceDirectory = Str::plural($this->argument('name'));

        if ($this->argument('kit_namespace')) {
            return config('rest-presenter.generator.namespace') . '\\' . $this->argument('kit_namespace');
        }

        return config('rest-presenter.generator.namespace') . '\\Resources\\' . $resourceDirectory;
    }

    protected function getNameInput(): string
    {
        return trim($this->argument('name')) . 'ResourceController';
    }

    protected function buildClass($name): string
    {
        $replace = $this->buildResourceReplacements();

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    protected function buildResourceReplacements(): array
    {
        return [
            '{{ resourceNamespace }}' => $this->argument('kit_namespace')
                ? 'XtendPackages\\RESTPresenter\\' . $this->argument('kit_namespace') . '\\' . $this->getNameInput()
                : 'XtendPackages\\RESTPresenter\\Resources\\' . Str::plural($this->argument('name')) . '\\' . $this->getNameInput(),
            '{{ aliasResource }}' => 'Xtend' . $this->getNameInput(),
            '{{ modelClassImport }}' => $this->argument('model'),
            '{{ modelClassName }}' => class_basename($this->argument('model')),
            '{{ $modelVarSingular }}' => strtolower(class_basename($this->argument('model'))),
            '{{ $modelVarPlural }}' => strtolower(Str::plural(class_basename($this->argument('model')))),
            '{{ filters }}' => $this->filters->map(
                fn ($filter) => "'$filter' => Filters\\" . ucfirst($filter) . '::class',
            )->implode(",\n\t\t\t"),
            '{{ presenters }}' => $this->presenters->keys()->map(
                fn ($presenter) => 'Presenters\\' . ucfirst($presenter) . '\\' . ucfirst($presenter) . '::class',
            )->implode(",\n\t\t\t"),
        ];
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the ' . strtolower($this->type)],
            ['type', InputArgument::OPTIONAL, 'The type of resource to create'],
            ['model', InputArgument::OPTIONAL, 'The model that the resource references'],
            ['kit_namespace', InputArgument::OPTIONAL, 'The namespace of the ' . strtolower($this->type)],
        ];
    }

    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'name' => [
                'Whats the name of your resource? (singular)',
                'e.g. Post',
            ],
        ];
    }

    protected function afterPromptingForMissingArguments(InputInterface $input, OutputInterface $output): void
    {
        if ($this->didReceiveOptions($input)) {
            return;
        }

        $this->promptForResourceType($input);
        $this->promptForModel($input);
        $this->promptForFilters($input);
        $this->promptForPresenters($input);
    }

    protected function promptForResourceType(InputInterface $input): void
    {
        $type = select('Which type of resource would you like to create?', [
            'new' => 'New resource',
            'extend' => 'Extend existing resource',
        ]);

        $input->setArgument('type', $type);
    }

    protected function promptForModel(InputInterface $input): void
    {
        $model = search(
            label: 'Which model should the resource use?',
            options: fn () => $this->scanModels(),
            placeholder: 'Search for a model...',
            hint: 'Press <comment>Enter</> to select the model.'
        );

        $this->setModel($model);

        $input->setArgument('model', $model);
    }

    protected function promptForFilters(InputInterface $input): void
    {
        $suggestFilters = $this->generateFilterSuggestions();

        if ($suggestFilters->isNotEmpty()) {
            $selectedFilters = multiselect(
                label: 'Here are some suggested filters to add to your resource:',
                options: $suggestFilters,
                default: $suggestFilters->keys(),
                hint: 'Press <comment>Enter</> to select the filters.'
            );

            $this->filters = $suggestFilters->only($selectedFilters);
        }

        $addMoreFilters = select(
            label: 'Would you like to add any custom filters for this resource?',
            options: ['Yes', 'No'],
            hint: 'Press <comment>Enter</> to select an option.'
        );

        if ($addMoreFilters === 'Yes') {
            $this->promptForCustomFilters($input);
        }
    }

    protected function promptForCustomFilters(InputInterface $input): void
    {
        $customFilter = text(
            label: 'Enter the custom filter you would like to add to your resource:',
            placeholder: 'e.g. AnotherFilter, CustomFilter, SomeOtherFilter',
            hint: 'Press <comment>Enter</> to confirm the filter name.'
        );

        $this->filters->put($customFilter, 'Custom');

        $addAnother = select(
            label: 'Add another custom filter?',
            options: ['Yes', 'No'],
            hint: 'Press <comment>Enter</> to select an option.'
        );

        if ($addAnother === 'Yes') {
            $this->promptForCustomFilters($input);
        }
    }

    protected function promptForPresenters(InputInterface $input, $init = true): void
    {
        $setupPresenters = select(
            label: 'Would you like to setup presenters for this resource?',
            options: ['Yes', 'No'],
            hint: 'Press <comment>Enter</> to select an option.'
        );

        if ($setupPresenters === 'No') {
            return;
        }

        $presenterName = text(
            label: 'Enter the name of the presenter you would like to add to your resource:',
            placeholder: 'e.g. Article, FeaturedPost',
            default: $init ? class_basename($this->argument('model')) : '',
            hint: 'Press <comment>Enter</> to confirm the presenter name.'
        );

        if ($presenterName) {
            $fields = $this->generateModelFields();
            $selectedFields = multiselect(
                label: 'Select the fields you would like to include in the presenter:',
                options: $fields->keys()->toArray(),
                default: $fields->keys(),
                hint: 'Press <comment>Enter</> to select the fields.'
            );
            $presenterFields = $fields->only($selectedFields)->toArray();
        }

        $this->presenters ??= collect();
        $this->presenters->put($presenterName, $presenterFields ?? []);

        $addAnother = select(
            label: 'Add another custom presenter?',
            options: ['Yes', 'No'],
            hint: 'Press <comment>Enter</> to select an option.'
        );

        if ($addAnother === 'Yes') {
            $this->promptForPresenters($input, false);
        }
    }

    protected function generateModelFields(): Collection
    {
        $table = $this->model->getTable();

        return $this->getTableColumns($table)->mapWithKeys(
            fn ($column) => [$column => Schema::getColumnType($table, $column)],
        );
    }

    protected function setModel(string $model): void
    {
        $this->model = resolve($model);
    }

    protected function generateFilterSuggestions(): Collection
    {
        $reflect = new ReflectionClass($this->model);

        return collect($reflect->getMethods(ReflectionMethod::IS_PUBLIC))
            ->filter(function (ReflectionMethod $method) {
                $returnNamedType = $method->getReturnType() instanceof ReflectionNamedType ? $method->getReturnType()->getName() : null;

                return is_subclass_of($returnNamedType, Relation::class);
            })
            ->mapWithKeys(function (ReflectionMethod $method) {
                $returnNamedType = $method->getReturnType() instanceof ReflectionNamedType ? $method->getReturnType()->getName() : null;

                return [
                    $method->getName() . '=>' . class_basename($returnNamedType) => $method->getName(),
                ];
            });
    }

    protected function scanModels(): array
    {
        return collect(app('files')->allFiles(app_path()))
            ->filter(fn (SplFileInfo $file) => Str::endsWith($file->getFilename(), '.php'))
            ->map(fn (SplFileInfo $file) => $file->getRelativePathname())
            ->map(fn (string $file) => new ReflectionClass('App\\' . str_replace(['/', '.php'], ['\\', ''], $file)))
            ->filter(fn (ReflectionClass $class) => $class->isSubclassOf(Model::class))
            ->mapWithKeys(fn (ReflectionClass $class) => [$class->getName() => $class->getShortName()])
            ->toArray();
    }
}
