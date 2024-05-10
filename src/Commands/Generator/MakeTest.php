<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Commands\Generator;

use Composer\InstalledVersions;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function Laravel\Prompts\select;

#[AsCommand(name: 'rest-presenter:make-test')]
final class MakeTest extends GeneratorCommand
{
    protected $name = 'rest-presenter:make-test';

    protected $description = 'Create a new test';

    protected $type = 'Test';

    /**
     * {@inheritDoc}
     */
    public function handle(): ?bool
    {
        parent::handle();

        return null;
    }

    protected function getStub(): string
    {
        $testFramework = InstalledVersions::isInstalled('pestphp/pest') ? 'pest' : 'test';

        return __DIR__.'/stubs/tests/'.type($this->argument('type'))->asString().'.'.$testFramework.'.php.stub';
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        if ($this->argument('kit_namespace')) {
            $kitNamespace = type($this->argument('kit_namespace'))->asString();
            $kitPath = Str::of($kitNamespace)->replace('\\', '/')->__toString();

            return base_path('tests').'/'.$kitPath.'/'.$this->getNameInput().'.php';
        }

        return base_path('tests').'/'.$this->getNameInput().'.php';
    }

    protected function getNameInput(): string
    {
        return type($this->argument('name'))->asString();
    }

    protected function buildClass($name): string
    {
        $replace = $this->buildTestReplacements();

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
            ['name', InputArgument::REQUIRED, 'The name of the Test'],
            ['model', InputArgument::REQUIRED, 'The name of the model'],
            ['type', InputArgument::OPTIONAL, 'The type of test'],
            ['test_type', InputArgument::OPTIONAL, 'The type of test'],
            ['kit_namespace', InputArgument::OPTIONAL, 'The name of the kit namespace'],
        ];
    }

    protected function afterPromptingForMissingArguments(InputInterface $input, OutputInterface $output): void
    {
        if ($this->didReceiveOptions($input)) {
            return;
        }

        $type = select('Which type of test would you like to create?', [
            'unit' => 'Unit',
            'feature' => 'Feature',
        ]);

        $input->setOption(type($type)->asString(), true);
    }

    /**
     * @return array<string, string>
     */
    private function buildTestReplacements(): array
    {
        $namespace = type(config('rest-presenter.generator.namespace'))->asString();
        $kitNamespace = type($this->argument('kit_namespace'))->asString();
        $modelName = type($this->argument('model'))->asString();
        $modelClass = Str::of(class_basename($modelName));
        $defaultPresenter = $modelClass->plural()->ucfirst()->value();

        return [
            '{{ testNamespace }}' => 'Tests\\'.$kitNamespace,
            '{{ testClassName }}' => $this->getNameInput(),
            '{{ dataResponseClassImport }}' => $namespace.'\\'.$kitNamespace.'\\Presenters\\'.$defaultPresenter.'\\Data\\'.$modelClass->value().'Data',
            '{{ authenticationApiUserFunction }}' => 'authenticateApiUser();',
            '{{ modelClassImport }}' => $modelName,
            '{{ modelClassName }}' => $modelClass->value(),
            '{{ $modelVarSingular }}' => $modelClass->lcfirst()->value(),
            '{{ $modelVarPlural }}' => $modelClass->plural()->lcfirst()->value(),
        ];
    }
}
