<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Support\ApiCollection\Exporters\Insomnia;

use XtendPackages\RESTPresenter\Support\ApiCollection\Concerns\InteractsWithRoutes;
use XtendPackages\RESTPresenter\Support\ApiCollection\Exporters\BaseExporter;
use XtendPackages\RESTPresenter\Support\ApiCollection\Exporters\ExporterContract;

class Exporter extends BaseExporter implements ExporterContract
{
    use InteractsWithRoutes;

    /**
     * @param  array<string, array<mixed>>  $config
     */
    public function __construct(protected array $config)
    {
    }

    public function schema(): void
    {
        // @phpstan-ignore-next-line
        $this->schema = [
            '_type' => 'export',
            '__export_format' => 4,
            '__export_date' => now()->toIso8601String(),
            '__export_source' => 'rest-presenter.insomnia',
            'resources' => [],
        ];

        // @phpstan-ignore-next-line
        $this->schema = $this->prepareThroughPipeline(
            passable: $this->schema,
            pipes: [
                Workspace::class,
                Requests::class,
                Environments::class,
            ],
        );
    }
}
