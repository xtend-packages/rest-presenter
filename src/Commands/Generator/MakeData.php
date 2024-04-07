<?php

namespace XtendPackages\RESTPresenter\Commands\Generator;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use XtendPackages\RESTPresenter\Concerns\InteractsWithDbSchema;
use XtendPackages\RESTPresenter\Concerns\WithTypeScriptGenerator;

use function Laravel\Prompts\select;

#[AsCommand(name: 'rest-presenter:make-data')]
class MakeData extends GeneratorCommand
{
    use InteractsWithDbSchema;
    use WithTypeScriptGenerator;

    protected $name = 'rest-presenter:make-data';

    protected $description = 'Create a new data class';

    protected $type = 'Data';

    public function handle()
    {
        parent::handle();

        $this->generateTypeScriptDeclarations();
    }

    protected function qualifyClass($name)
    {
        $name = ltrim($name, '\\/');

        $name = str_replace('/', '\\', $name);

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($name, $rootNamespace)) {

            return $name;
        }

        return $this->getDefaultNamespace($rootNamespace) . '\\' . $name;
    }

    protected function getStub(): string
    {
        return __DIR__ . '/stubs/' . $this->argument('type') . '/data.php.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        $resourceDirectory = Str::plural($this->argument('resource'));

        if ($this->argument('kit_namespace')) {
            return config('rest-presenter.generator.namespace') . '\\' . $this->argument('kit_namespace') . '\\Presenters\\' . $this->argument('presenter') . '\\Data';
        }

        return config('rest-presenter.generator.namespace') . '\\Resources\\' . $resourceDirectory . '\\Presenters\\' . $this->argument('presenter') . '\\Data';
    }

    protected function getNameInput(): string
    {
        return $this->argument('name');
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
            '{{ presenterNamespace }}' => $this->argument('kit_namespace')
                ? 'XtendPackages\\RESTPresenter\\' . $this->argument('kit_namespace') . '\\Presenters\\' . $this->getNameInput() . '\\' . $this->getNameInput()
                : 'XtendPackages\\RESTPresenter\\Resources\\' . Str::plural($this->argument('name')) . '\\Presenters\\' . $this->getNameInput() . '\\' . $this->getNameInput(),
            '{{ aliasPresenter }}' => 'Xtend' . $this->getNameInput() . 'Presenter',
            '{{ modelClassImport }}' => $this->argument('model'),
            '{{ modelClassName }}' => class_basename($this->argument('model')),
            '{{ $modelVarSingular }}' => strtolower(class_basename($this->argument('model'))),
            '{{ $modelVarPlural }}' => strtolower(Str::plural(class_basename($this->argument('model')))),
            '{{ properties }}' => $this->transformFieldProperties($this->argument('fields')),
        ];
    }

    protected function transformFieldProperties(array $fields): string
    {
        return collect($fields)->map(function (array $fieldProperties, string $field) {
            $fieldType = strtolower($fieldProperties['type']);
            $propertyType = match ($fieldType) {
                'int', 'integer', 'bigint' => 'int',
                'tinyint' => 'bool',
                'timestamp', 'datetime' => 'Carbon',
                'json' => 'array',
                default => 'string',
            };

            $nullable = $this->isFieldNullable($fieldProperties);

            if ($nullable) {
                $propertyType = '?' . $propertyType;
            }
            $tsOptional = $nullable ? "#[TypeScriptOptional]\n\t\t" : '';

            return $tsOptional . "public {$propertyType} \${$field}";
        })->implode(",\n\t\t");
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the ' . strtolower($this->type)],
            ['resource', InputArgument::REQUIRED, 'The resource of the ' . strtolower($this->type)],
            ['type', InputArgument::OPTIONAL, 'The type of filter to create'],
            ['model', InputArgument::OPTIONAL, 'The model class to use'],
            ['presenter', InputArgument::OPTIONAL, 'The presenter class to use'],
            ['fields', InputArgument::OPTIONAL, 'The fields to include in the presenter'],
            ['kit_namespace', InputArgument::OPTIONAL, 'The namespace of the ' . strtolower($this->type)],
        ];
    }

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
            $input->setOption($type, true);
        }
    }
}
