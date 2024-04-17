<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Concerns;

use Illuminate\Config;
use Illuminate\Support\Str;
use XtendPackages\RESTPresenter\Commands\Generator\Writers\ModuleWriter;

trait WithTypeScriptGenerator
{
    private Config\Repository $config;

    public function generateTypeScriptDeclarations(): void
    {
        $this->config = $this->laravel->make('config');

        $this->ensureSpatieEnumTransformerIsRemoved();
        $this->useCustomModuleTypeScriptWriter();
        $this->disableAutoDiscovery();

        $this->call('typescript:transform', [
            '--path' => $this->getDataPath(),
            '--output' => $this->getOutputPath(),
            // '--format' => true,
        ]);
    }

    protected function getDataPath(): string
    {
        $config = [
            'namespace' => type(config('rest-presenter.generator.namespace'))->asString(),
            'path' => type(config('rest-presenter.generator.path'))->asString(),
        ];

        return Str::of($this->getDefaultNamespace($this->rootNamespace()))
            ->replace($config['namespace'], $config['path'])
            ->replace('app/', '')
            ->replace('\\', '/')
            ->value();
    }

    protected function getOutputPath(): string
    {
        return Str::of(type($this->argument('name'))->asString())
            ->kebab()
            ->prepend(config('rest-presenter.generator.ts_types_path').'/')
            ->append('.d.ts')
            ->value();
    }

    protected function ensureSpatieEnumTransformerIsRemoved(): void
    {
        $transformersFromConfig = type($this->config['typescript-transformer'] ?? [])->asArray();
        if (! $transformersFromConfig['transformers']) {
            return;
        }

        /** @var array<int, string> $transformerArray */
        $transformerArray = $transformersFromConfig['transformers'];

        $transformers = collect($transformerArray)->reject(fn ($transformer): bool => $transformer === \Spatie\TypeScriptTransformer\Transformers\SpatieEnumTransformer::class)->values()->all();

        $this->config->set('typescript-transformer.transformers', $transformers);
    }

    protected function useCustomModuleTypeScriptWriter(): void
    {
        $this->config->set('typescript-transformer.writer', ModuleWriter::class);
    }

    protected function disableAutoDiscovery(): void
    {
        $this->config->set('typescript-transformer.auto_discover_types', []);
    }
}
