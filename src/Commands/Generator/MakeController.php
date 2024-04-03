<?php

namespace XtendPackages\RESTPresenter\Commands\Generator;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function Laravel\Prompts\select;

#[AsCommand(name: 'rest-presenter:make-controller')]
class MakeController extends GeneratorCommand
{
    protected $name = 'rest-presenter:make-controller';

    protected $description = 'Create a new controller class';

    protected $type = 'Controller';

    protected function getStub(): string
    {
        return __DIR__ . '/stubs/' . $this->argument('type') . '/controller.php.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        $controllerDirectory = Str::plural($this->argument('name'));

        if ($this->argument('kit_namespace')) {
            return config('rest-presenter.generator.namespace') . '\\' . $this->argument('kit_namespace');
        }

        return config('rest-presenter.generator.namespace') . '\\Controllers\\' . $controllerDirectory;
    }

    protected function getNameInput(): string
    {
        return trim($this->argument('name'));
    }

    protected function buildClass($name): string
    {
        $replace = $this->buildControllerReplacements();

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    protected function buildControllerReplacements(): array
    {
        return [
            '{{ controllerNamespace }}' => $this->argument('kit_namespace')
                ? 'XtendPackages\\RESTPresenter\\' . $this->argument('kit_namespace') . '\\' . $this->getNameInput()
                : 'XtendPackages\\RESTPresenter\\Controllers\\' . Str::plural($this->argument('name')) . '\\' . $this->getNameInput(),
            '{{ aliasController }}' => 'Xtend' . $this->getNameInput(),
        ];
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the ' . strtolower($this->type)],
            ['type', InputArgument::OPTIONAL, 'The type of the ' . strtolower($this->type)],
            ['kit_namespace', InputArgument::OPTIONAL, 'The namespace of the ' . strtolower($this->type)],
        ];
    }

    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'name' => [
                'What should the controller be named?',
                'e.g. AuthController',
            ],
        ];
    }

    protected function afterPromptingForMissingArguments(InputInterface $input, OutputInterface $output): void
    {
        if ($this->didReceiveOptions($input)) {
            return;
        }

        $type = select('Which type of controller would you like to create?', [
            'new' => 'New controller',
            'extend' => 'Extend existing controller',
        ]);

        if ($type !== 'new') {
            $input->setOption($type, true);
        }
    }
}
