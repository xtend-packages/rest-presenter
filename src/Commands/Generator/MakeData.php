<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Commands\Generator;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use XtendPackages\RESTPresenter\Concerns\InteractsWithDbSchema;
use XtendPackages\RESTPresenter\Concerns\WithTypeScriptGenerator;

use function Laravel\Prompts\select;

#[AsCommand(name: 'rest-presenter:make-data')]
final class MakeData extends GeneratorCommand
{
    use InteractsWithDbSchema;
    use WithTypeScriptGenerator;

    protected $name = 'rest-presenter:make-data';

    protected $description = 'Create a new data class';

    protected $type = 'Data';

    /**
     * {@inheritDoc}
     */
    public function handle(): ?bool
    {
        parent::handle();

        $this->generateTypeScriptDeclarations();

        return null;
    }

    protected function qualifyClass($name)
    {
        $name = ltrim($name, '\\/');

        $name = str_replace('/', '\\', $name);

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($name, $rootNamespace)) {

            return $name;
        }

        return $this->getDefaultNamespace($rootNamespace).'\\'.$name;
    }

    protected function getStub(): string
    {
        return __DIR__.'/stubs/'.type($this->argument('type'))->asString().'/data.php.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        $resourceName = type($this->argument('resource'))->asString();
        $namespace = type(config('rest-presenter.generator.namespace'))->asString();
        $presenterName = type($this->argument('presenter'))->asString();
        $resourceDirectory = Str::plural($resourceName);

        if ($this->argument('kit_namespace')) {
            return $namespace.'\\'.type($this->argument('kit_namespace'))->asString().'\\Presenters\\'.$presenterName.'\\Data';
        }

        return $namespace.'\\Resources\\'.$resourceDirectory.'\\Presenters\\'.$presenterName.'\\Data';
    }

    protected function getNameInput(): string
    {
        return type($this->argument('name'))->asString();
    }

    protected function buildClass($name): string
    {
        $replace = $this->buildResourceReplacements();

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    /**
     * @return array<int, array<int, int|string>>
     */
    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the '.strtolower($this->type)],
            ['resource', InputArgument::REQUIRED, 'The resource of the '.strtolower($this->type)],
            ['type', InputArgument::REQUIRED, 'The type of filter to create'],
            ['model', InputArgument::OPTIONAL, 'The model class to use'],
            ['presenter', InputArgument::OPTIONAL, 'The presenter class to use'],
            ['fields', InputArgument::OPTIONAL, 'The fields to include in the presenter'],
            ['kit_namespace', InputArgument::OPTIONAL, 'The namespace of the '.strtolower($this->type)],
        ];
    }

    /**
     * @return array<string, array<int, string>>
     */
    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'name' => [
                'What should the data class be named?',
                'e.g. ProfileData',
            ],
        ];
    }

    protected function afterPromptingForMissingArguments(InputInterface $input, OutputInterface $output): void
    {
        if ($this->didReceiveOptions($input)) {
            return;
        }

        $type = select('Which type of data class would you like to create?', [
            'new' => 'New data',
            'extend' => 'Extend existing data',
        ]);

        if ($type !== 'new') {
            $input->setOption(type($type)->asString(), true);
        }
    }

    /**
     * @return array<string, string>
     */
    private function buildResourceReplacements(): array
    {
        $resourceName = type($this->argument('name'))->asString();
        $model = type($this->argument('model'))->asString();
        $fields = type($this->argument('fields') ?? [])->asArray();

        return [
            '{{ presenterNamespace }}' => $this->argument('kit_namespace')
                ? 'XtendPackages\\RESTPresenter\\'.type($this->argument('kit_namespace'))->asString().'\\Presenters\\'.$this->getNameInput().'\\'.$this->getNameInput()
                : 'XtendPackages\\RESTPresenter\\Resources\\'.Str::plural($resourceName).'\\Presenters\\'.$this->getNameInput().'\\'.$this->getNameInput(),
            '{{ aliasPresenter }}' => 'Xtend'.$this->getNameInput().'Presenter',
            '{{ modelClassImport }}' => $model,
            '{{ modelClassName }}' => class_basename($model),
            '{{ $modelVarSingular }}' => strtolower(class_basename($model)),
            '{{ $modelVarPlural }}' => strtolower(Str::plural(class_basename($model))),
            '{{ properties }}' => $this->transformFieldProperties($fields, $model),
        ];
    }

    /**
     * @param  array<string, array<string, mixed>>  $fields
     */
    private function transformFieldProperties(array $fields, string $model): string
    {
        return collect($fields)->map(function (array $fieldProperties, string $field) use ($model): string {
            $fieldType = strtolower(type($fieldProperties['type'])->asString());
            $propertyType = match ($fieldType) {
                'int', 'integer', 'bigint' => 'int',
                'tinyint' => 'bool',
                'timestamp', 'datetime' => 'Carbon',
                'json' => 'array',
                default => 'string',
            };

            $model = type(new $model)->as(Model::class);
            if (array_key_exists($field, $model->getCasts()) && $propertyType !== 'array') {
                $propertyType = 'string';
            }

            $nullable = $this->isFieldNullable($fieldProperties);

            if ($nullable) {
                $propertyType = '?'.$propertyType;
            }

            $tsOptional = $nullable ? "#[TypeScriptOptional]\n\t\t" : '';

            return $tsOptional."public {$propertyType} \${$field}";
        })->implode(",\n\t\t");
    }
}
