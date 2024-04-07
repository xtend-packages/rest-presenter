<?php

namespace XtendPackages\RESTPresenter\Concerns;

use Illuminate\Support\Str;
use XtendPackages\RESTPresenter\Commands\Generator\Writers\ModuleWriter;

trait WithTypeScriptGenerator
{
    public function generateTypeScriptDeclarations(): void
    {
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
        return Str::of($this->getDefaultNamespace($this->rootNamespace()))
            ->replace(config('rest-presenter.generator.namespace'), config('rest-presenter.generator.path'))
            ->replace('app/', '')
            ->replace('\\', '/')
            ->value();
    }

    protected function getOutputPath(): string
    {
        return Str::of($this->argument('name'))
            ->snake('-')
            ->prepend(config('rest-presenter.generator.ts_types_path') . '/')
            ->append('.d.ts')
            ->value();
    }

    protected function ensureSpatieEnumTransformerIsRemoved(): void
    {
        $app = $this->laravel;

        $transformers = collect($app['config']['typescript-transformer']['transformers'])->reject(function ($transformer) {
            return $transformer === 'Spatie\TypeScriptTransformer\Transformers\SpatieEnumTransformer';
        })->values()->all();

        $app['config']->set('typescript-transformer.transformers', $transformers);
    }

    protected function useCustomModuleTypeScriptWriter(): void
    {
        $app = $this->laravel;

        $app['config']->set('typescript-transformer.writer', ModuleWriter::class);
    }

    protected function disableAutoDiscovery(): void
    {
        $app = $this->laravel;

        $app['config']->set('typescript-transformer.auto_discover_types', []);
    }
}
