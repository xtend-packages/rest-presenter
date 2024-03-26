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

    protected function getStub(): string
    {
        return __DIR__ . '/stubs/presenter.php.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        $resourceDirectory = Str::plural($this->argument('name'));

        if ($this->argument('kit_namespace')) {
            return config('rest-presenter.generator.namespace') . '\\' . $this->argument('kit_namespace') . '\\Presenters\\' . $this->argument('name');
        }

        return config('rest-presenter.generator.namespace') . '\\Resources\\' . $resourceDirectory . '\\Presenters\\' . $this->argument('name');
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
        ];
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the ' . strtolower($this->type)],
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
