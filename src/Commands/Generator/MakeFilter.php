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
            default => 'attribute',
        };

        return __DIR__ . '/stubs/' . $this->argument('type') . '/filter/' . $filterStub . '.php.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        $resourceDirectory = Str::plural($this->argument('resource'));

        if ($this->argument('kit_namespace')) {
            return config('rest-presenter.generator.namespace') . '\\' . $this->argument('kit_namespace') . '\\Filters';
        }

        return config('rest-presenter.generator.namespace') . '\\Resources\\' . $resourceDirectory . '\\Filters';
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
            '{{ filterNamespace }}' => $this->argument('kit_namespace')
                ? 'XtendPackages\\RESTPresenter\\' . $this->argument('kit_namespace') . '\\Filters\\' . $this->getNameInput() . '\\' . $this->getNameInput()
                : 'XtendPackages\\RESTPresenter\\Resources\\' . Str::plural($this->argument('name')) . '\\Filters\\' . $this->getNameInput() . '\\' . $this->getNameInput(),
            '{{ aliasFilter }}' => 'Xtend' . $this->getNameInput() . 'Filter',
            '{{ relationship }}' => strtolower($this->argument('name')),
        ];
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the ' . strtolower($this->type)],
            ['resource', InputArgument::REQUIRED, 'The resource of the ' . strtolower($this->type)],
            ['type', InputArgument::OPTIONAL, 'The type of filter to create'],
            ['relation', InputArgument::OPTIONAL, 'The relation of the ' . strtolower($this->type)],
            ['kit_namespace', InputArgument::OPTIONAL, 'The namespace of the ' . strtolower($this->type)],
        ];
    }
}
