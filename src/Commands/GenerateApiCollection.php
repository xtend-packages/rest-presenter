<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Attribute\AsCommand;
use XtendPackages\RESTPresenter\Concerns\InteractsWithGit;
use XtendPackages\RESTPresenter\Support\ApiCollection\Exporters\ExporterContract;

use function Laravel\Prompts\confirm;

#[AsCommand(name: 'rest-presenter:generate-api-collection')]
final class GenerateApiCollection extends Command
{
    use InteractsWithGit;

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

        if (confirm(__('Would you like to auto-commit the generated API collection?'))) {
            $this->gitAutoCommit = $this->isCleanWorkingDirectory();
        }

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

        if ($this->gitAutoCommit) {
            $this->commitChanges(__('feat: API Collection for :exporter', [
                'exporter' => type(config('rest-presenter.exporters.provider'))->asString(),
            ]));
        }
    }

    private function generateFilePath(): string
    {
        $exporter = type(config('rest-presenter.exporters.provider'))->asString();
        $path = resource_path('rest-presenter/'.$exporter);

        $this->filesystem->ensureDirectoryExists(type($path)->asString());

        return $path.'/'.date('Y_m_d_Hi').'_'.$exporter.'_collection.json';
    }
}
