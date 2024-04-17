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

#[AsCommand(name: 'rest-presenter:make-controller')]
final class MakeController extends GeneratorCommand
{
    protected $name = 'rest-presenter:make-controller';

    protected $description = 'Create a new controller class';

    protected $type = 'Controller';

    protected function getStub(): string
    {
        return __DIR__.'/stubs/'.type($this->argument('type'))->asString().'/controller.php.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        $controllerName = type($this->argument('name'))->asString();
        $controllerDirectory = Str::plural($controllerName);
        $namespace = type(config('rest-presenter.generator.namespace'))->asString();
        $kitNamespace = type($this->argument('kit_namespace'))->asString();

        if ($kitNamespace !== '' && $kitNamespace !== '0') {
            return $namespace.'\\'.$kitNamespace;
        }

        return $namespace.'\\Controllers\\'.$controllerDirectory;
    }

    protected function getNameInput(): string
    {
        return trim(type($this->argument('name'))->asString());
    }

    protected function buildClass($name): string
    {
        $replace = $this->buildControllerReplacements();

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
            ['type', InputArgument::REQUIRED, 'The type of the '.strtolower($this->type)],
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
            $input->setOption(type($type)->asString(), true);
        }
    }

    /**
     * @return array<string, string>
     */
    private function buildControllerReplacements(): array
    {
        $controllerName = type($this->argument('name'))->asString();

        return [
            '{{ controllerNamespace }}' => $this->argument('kit_namespace')
                ? 'XtendPackages\\RESTPresenter\\'.type($this->argument('kit_namespace'))->asString().'\\'.$this->getNameInput()
                : 'XtendPackages\\RESTPresenter\\Controllers\\'.$controllerName.'\\'.$this->getNameInput(),
            '{{ aliasController }}' => 'Xtend'.$this->getNameInput(),
        ];
    }
}
