<?php

namespace XtendPackages\RESTPresenter\Commands\Generator;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function Laravel\Prompts\select;

#[AsCommand(name: 'rest-presenter:make-presenter')]
class MakePresenter extends GeneratorCommand
{
    protected $name = 'rest-presenter:make-presenter';

    protected $description = 'Create a new presenter class';

    protected $type = 'Presenter';

    public function handle()
    {
        if ($this->hasArgument('fields') && is_array($this->argument('fields'))) {
            $presenter = Str::of($this->argument('name'))->replace('Presenter', '');
            $this->call('rest-presenter:make-data', [
                'type' => 'new',
                'name' => $presenter->singular()->value() . 'Data',
                'resource' => $this->argument('resource'),
                'fields' => $this->argument('fields'),
                'model' => $this->argument('model'),
                'presenter' => $presenter->plural()->value(),
                'kit_namespace' => $this->argument('kit_namespace'),
            ]);
        }

        parent::handle();
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
        return __DIR__ . '/stubs/' . $this->argument('type') . '/presenter.php.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return config('rest-presenter.generator.namespace') . '\\' . $this->getPresenterNamespace();
    }

    protected function getPresenterNamespace(): string
    {
        $resourceDirectory = 'Resources\\' . Str::plural($this->argument('resource'));
        $presenterNamespace = Str::of($this->argument('name'))
            ->replace('Presenter', '')
            ->plural()
            ->value();

        $resourceNamespace = $this->argument('kit_namespace')
            ? $this->argument('kit_namespace')
            : $resourceDirectory;

        return $resourceNamespace . '\\Presenters\\' . $presenterNamespace;
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
            '{{ dataClass }}' => Str::of($this->argument('name'))->remove('Presenter')->append('Data')->value(),
        ];
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the ' . strtolower($this->type)],
            ['resource', InputArgument::OPTIONAL, 'The resource of the ' . strtolower($this->type)],
            ['type', InputArgument::OPTIONAL, 'The type of filter to create'],
            ['model', InputArgument::OPTIONAL, 'The model class to use'],
            ['fields', InputArgument::OPTIONAL, 'The fields to include in the presenter'],
            ['kit_namespace', InputArgument::OPTIONAL, 'The namespace of the ' . strtolower($this->type)],
        ];
    }

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
            $input->setOption($type, true);
        }
    }
}
