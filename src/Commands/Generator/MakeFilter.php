<?php

namespace XtendPackages\RESTPresenter\Commands\Generator;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(name: 'rest-presenter:make-filter')]
class MakeFilter extends GeneratorCommand
{
    protected $name = 'rest-presenter:make-filter';

    protected $description = 'Create a new filter class';

    protected $type = 'Filter';

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
        $filterStub = match ($this->argument('relation')) {
            'BelongsTo' => 'relation-belongs-to',
            'HasMany' => 'relation-has-many',
            'MorphToMany' => 'relation-morph-to-many',
            default => 'attribute',
        };

        return __DIR__ . '/stubs/' . type($this->argument('type'))->asString() . '/filter/' . $filterStub . '.php.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        $resourceName = type($this->argument('resource'))->asString();
        $resourceDirectory = Str::plural($resourceName);
        $namespace = type(config('rest-presenter.generator.namespace'))->asString();

        if ($this->argument('kit_namespace')) {
            return $namespace . '\\' . type($this->argument('kit_namespace'))->asString() . '\\Filters';
        }

        return $namespace . '\\Resources\\' . $resourceDirectory . '\\Filters';
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
        $resourceName = type($this->argument('name'))->asString();

        return [
            '{{ filterNamespace }}' => $this->argument('kit_namespace')
                ? 'XtendPackages\\RESTPresenter\\' . type($this->argument('kit_namespace'))->asString() . '\\Filters\\' . $this->getNameInput() . '\\' . $this->getNameInput()
                : 'XtendPackages\\RESTPresenter\\Resources\\' . Str::plural($resourceName) . '\\Filters\\' . $this->getNameInput() . '\\' . $this->getNameInput(),
            '{{ aliasFilter }}' => 'Xtend' . $this->getNameInput() . 'Filter',
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
            ['type', InputArgument::OPTIONAL, 'The type of filter to create'],
            ['relation', InputArgument::OPTIONAL, 'The relation of the ' . strtolower($this->type)],
            ['relation_search_key', InputArgument::OPTIONAL, 'The search key of the ' . strtolower($this->type)],
            ['kit_namespace', InputArgument::OPTIONAL, 'The namespace of the ' . strtolower($this->type)],
        ];
    }
}
