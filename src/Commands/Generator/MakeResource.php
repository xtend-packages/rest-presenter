<?php

namespace XtendPackages\RESTPresenter\Commands\Generator;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function Laravel\Prompts\select;

#[AsCommand(name: 'rest-presenter:make-resource')]
class MakeResource extends GeneratorCommand
{
    protected $name = 'rest-presenter:make-resource';

    protected $description = 'Create a new resource class';

    protected $type = 'Resource';

    protected function getStub(): string
    {
        return __DIR__ . '/stubs/resource.controller.php.stub';
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
                'What should the resource be named?',
                'e.g. UserResourceController',
            ],
        ];
    }

    protected function afterPromptingForMissingArguments(InputInterface $input, OutputInterface $output): void
    {
        if ($this->didReceiveOptions($input)) {
            return;
        }

        $type = select('Which type of resource would you like to create?', [
            'new' => 'New resource',
            'extend' => 'Extend existing resource',
        ]);

        if ($type !== 'new') {
            $input->setOption($type, true);
        }
    }
}
