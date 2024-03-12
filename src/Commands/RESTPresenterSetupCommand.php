<?php

namespace XtendPackages\RESTPresenter\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class RESTPresenterSetupCommand extends Command
{
    protected $signature = 'rest-presenter:setup';

    protected $description = 'Setup REST Presenter & prepare API structure for your project';

    public function __construct(protected Filesystem $filesystem)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->initialSetup();
        $this->createApiStructure();

        $this->components->info('All done');

        return self::SUCCESS;
    }

    protected function initialSetup(): void
    {
        $this->info('Initial setup');
        $this->starGitHubRepo();
    }

    protected function createApiStructure(): void
    {
        $this->info('Creating API structure');
        $generatorPath = config('rest-presenter.generator.path');
        if (! $exists = $this->filesystem->isDirectory($path = $this->laravel->basePath($generatorPath))) {
            $this->filesystem->makeDirectory($path, 0755, true);
        }

        $this->components->info(
            $exists
                ? 'API structure already exists at '.$path
                : 'Created API structure at '.$path
        );
    }

    protected function starGitHubRepo(): void
    {
        if ($this->components->confirm('Would you like to star our repo on GitHub?')) {
            $repoUrl = 'https://github.com/xtend-packages/rest-presenter';

            if (PHP_OS_FAMILY == 'Darwin') {
                exec("open {$repoUrl}");
            }
            if (PHP_OS_FAMILY == 'Windows') {
                exec("start {$repoUrl}");
            }
            if (PHP_OS_FAMILY == 'Linux') {
                exec("xdg-open {$repoUrl}");
            }
        }
    }
}
