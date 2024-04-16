<?php

namespace XtendPackages\RESTPresenter\Commands\Generator;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(name: 'rest-presenter:make-action')]
class MakeAction extends GeneratorCommand
{
    protected $name = 'rest-presenter:make-action';

    protected $description = 'Create a new action class';

    protected $type = 'Action';

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
        return __DIR__ . '/stubs/' . type($this->argument('type'))->asString() . '/action.php.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        $resourceName = type($this->argument('resource'))->asString();
        $resourceDirectory = Str::plural($resourceName);
        $kitNamespace = type($this->argument('kit_namespace'))->asString();
        $namespace = type(config('rest-presenter.generator.namespace'))->asString();

        if ($kitNamespace) {
            return $namespace . '\\' . $kitNamespace . '\\Actions';
        }

        return $namespace . '\\Resources\\' . $resourceDirectory . '\\Actions';
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
     * @return array<string, string>
     */
    protected function buildResourceReplacements(): array
    {
        $kitNamespace = type($this->argument('kit_namespace'))->asString();
        $resourceName = type($this->argument('name'))->asString();

        return [
            '{{ actionNamespace }}' => $kitNamespace
                ? 'XtendPackages\\RESTPresenter\\' . $kitNamespace . '\\Actions\\' . $this->getNameInput() . '\\' . $this->getNameInput()
                : 'XtendPackages\\RESTPresenter\\Resources\\' . Str::plural($resourceName) . '\\Actions\\' . $this->getNameInput() . '\\' . $this->getNameInput(),
            '{{ aliasAction }}' => 'Xtend' . $this->getNameInput() . 'Action',
            '{{ relationship }}' => strtolower($resourceName),
            '{{ relationship_search_key }}' => type($this->argument('relation_search_key') ?? '')->asString(),
        ];
    }

    /**
     * @return array<int, array<int, int|string>>
     */
    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the ' . strtolower($this->type)],
            ['resource', InputArgument::REQUIRED, 'The resource of the ' . strtolower($this->type)],
            ['type', InputArgument::REQUIRED, 'The type of action to create'],
            ['relation', InputArgument::OPTIONAL, 'The relation of the ' . strtolower($this->type)],
            ['relation_search_key', InputArgument::OPTIONAL, 'The search key of the ' . strtolower($this->type)],
            ['kit_namespace', InputArgument::OPTIONAL, 'The namespace of the ' . strtolower($this->type)],
        ];
    }
}
