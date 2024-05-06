<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Support\ApiCollection\Exporters\Postman;

use XtendPackages\RESTPresenter\Support\ApiCollection\Concerns\InteractsWithRoutes;
use XtendPackages\RESTPresenter\Support\ApiCollection\Exporters\BaseExporter;
use XtendPackages\RESTPresenter\Support\ApiCollection\Exporters\ExporterContract;

class Exporter extends BaseExporter implements ExporterContract
{
    use InteractsWithRoutes;

    public function __construct(protected array $config)
    {
    }

    public function schema(): void
    {
        $this->schema = [
            'info' => [
                'name' => config('rest-presenter.exporters.postman.info.name'),
                'schema' => config('rest-presenter.exporters.postman.info.schema'),
            ],
            'variable' => [],
            'item' => [],
            'event' => [],
        ];

        $this->schema = $this->prepareThroughPipeline(
            passable: $this->schema,
            pipes: [
                Variables::class,
                Items::class,
                Events::class,
            ],
        );
    }
}
