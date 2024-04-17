<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Commands\Generator;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function Laravel\Prompts\select;

#[AsCommand(name: 'rest-presenter:make-presenter')]
final class MakePresenter extends GeneratorCommand
{
    protected $name = 'rest-presenter:make-presenter';

    protected $description = 'Create a new presenter class';

    protected $type = 'Presenter';

    /**
     * {@inheritDoc}
     */
    public function handle(): ?bool
    {
        if ($this->hasArgument('fields') && is_array($this->argument('fields'))) {
            $name = type($this->argument('name'))->asString();
            $presenter = Str::of($name)->replace('Presenter', '');
            $this->call('rest-presenter:make-data', [
                'type' => 'new',
                'name' => $presenter->singular()->value().'Data',
                'resource' => $this->argument('resource'),
                'fields' => $this->argument('fields'),
                'model' => $this->argument('model'),
                'presenter' => $presenter->plural()->value(),
                'kit_namespace' => $this->argument('kit_namespace'),
            ]);
        }

        parent::handle();

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
        return __DIR__.'/stubs/'.type($this->argument('type'))->asString().'/presenter.php.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return config('rest-presenter.generator.namespace').'\\'.$this->getPresenterNamespace();
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
            ['type', InputArgument::REQUIRED, 'The type of filter to create'],
            ['resource', InputArgument::OPTIONAL, 'The resource of the '.strtolower($this->type)],
            ['model', InputArgument::OPTIONAL, 'The model class to use'],
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
                'What should the presenter be named?',
                'e.g. Profile',
            ],
        ];
    }

    protected function afterPromptingForMissingArguments(InputInterface $input, OutputInterface $output): void
    {
        if ($this->didReceiveOptions($input)) {
            return;
        }

        $type = select('Which type of presenter would you like to create?', [
            'new' => 'New presenter',
            'extend' => 'Extend existing presenter',
        ]);

        if ($type !== 'new') {
            $input->setOption(type($type)->asString(), true);
        }
    }

    private function getPresenterNamespace(): string
    {
        $resourceName = type($this->argument('resource'))->asString();
        $presenterName = type($this->argument('name'))->asString();
        $resourceDirectory = 'Resources\\'.Str::plural($resourceName);
        $presenterNamespace = Str::of($presenterName)
            ->replace('Presenter', '')
            ->plural()
            ->value();

        $resourceNamespace = $this->argument('kit_namespace') ?: $resourceDirectory;

        $resourceNamespace = type($resourceNamespace)->asString();

        return $resourceNamespace.'\\Presenters\\'.$presenterNamespace;
    }

    /**
     * @return array<string, string>
     */
    private function buildResourceReplacements(): array
    {
        $name = type($this->argument('name'))->asString();
        $model = type($this->argument('model'))->asString();

        return [
            '{{ presenterNamespace }}' => $this->argument('kit_namespace')
                ? 'XtendPackages\\RESTPresenter\\'.type($this->argument('kit_namespace'))->asString().'\\Presenters\\'.$this->getNameInput().'\\'.$this->getNameInput()
                : 'XtendPackages\\RESTPresenter\\Resources\\'.Str::plural($name).'\\Presenters\\'.$this->getNameInput().'\\'.$this->getNameInput(),
            '{{ aliasPresenter }}' => 'Xtend'.$this->getNameInput().'Presenter',
            '{{ modelClassImport }}' => $model,
            '{{ modelClassName }}' => class_basename($model),
            '{{ $modelVarSingular }}' => strtolower(class_basename($model)),
            '{{ $modelVarPlural }}' => strtolower(Str::plural(class_basename($model))),
            '{{ dataClass }}' => Str::of($name)->remove('Presenter')->append('Data')->value(),
        ];
    }
}
