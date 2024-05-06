<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Attribute\AsCommand;
use XtendPackages\RESTPresenter\Support\ApiCollection\Exporters\ExporterContract;

#[AsCommand(name: 'rest-presenter:generate-api-collection')]
final class GenerateApiCollection extends Command
{
    protected $signature = 'rest-presenter:generate-api-collection';

    protected $description = 'Exports API collection from your applications API routes';

    public function __construct(
        protected ExporterContract $exporter,
        protected Filesystem $filesystem,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Generating API Collection...');

        $this->exportApiCollection();

        return self::SUCCESS;
    }

    private function exportApiCollection(): void
    {
        $filePath = $this->generateFilePath();
        $this->exporter
            ->saveTo($filePath)
            ->export();

        $this->filesystem->put(
            path: $filePath,
            contents: $this->exporter->getSchema(),
        );

        $this->info('API Collection Exported: '.$filePath);
    }

    private function generateFilePath(): string
    {
        $exporter = type(config('rest-presenter.exporters.provider'))->asString();
        $path = resource_path('rest-presenter/'.$exporter);

        $this->filesystem->ensureDirectoryExists(type($path)->asString());

        return $path.'/'.date('Y_m_d_Hi').'_'.$exporter.'_collection.json';
    }
}
