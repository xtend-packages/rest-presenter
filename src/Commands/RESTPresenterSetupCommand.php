<?php

namespace XtendPackages\RESTPresenter\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;

use function Laravel\Prompts\confirm;

#[AsCommand(name: 'rest-presenter:setup')]
class RESTPresenterSetupCommand extends Command
{
    protected $signature = 'rest-presenter:setup';

    protected $description = 'Setup REST Presenter & prepare your API structure';

    public function __construct(protected Filesystem $filesystem)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->initialSetup();

        $this->starGitHubRepo();
        $this->sponsorThisProject();

        $this->components->info('REST Presenter setup completed successfully 🚀');

        return self::SUCCESS;
    }

    protected function initialSetup(): void
    {
        $this->components->info('Welcome to REST Presenter setup wizard');

        if (! $this->firstTimeSetup()) {
            $this->components->info('REST Presenter has already been setup. Now checking for updates...');
            $this->checkForUpdates();

            return;
        }

        $this->publishingConfig();
        $this->publishingServiceProvider();
        $this->publishingDefaultResources();
    }

    protected function starGitHubRepo(): void
    {
        if (confirm('Please star this project on GitHub 🤩')) {
            $repoUrl = 'https://github.com/xtend-packages/rest-presenter';

            match (PHP_OS_FAMILY) {
                'Darwin' => exec("open {$repoUrl}"),
                'Windows' => exec("start {$repoUrl}"),
                'Linux' => exec("xdg-open {$repoUrl}"),
            };

            $this->components->info('Thank you for your support!');
        }
    }

    protected function sponsorThisProject(): void
    {
        if (confirm('Show your support 💙 for Adam Lee and sponsor this project')) {
            $authorUrl = 'https://github.com/sponsors/adam-code-labx';

            match (PHP_OS_FAMILY) {
                'Darwin' => exec("open {$authorUrl}"),
                'Windows' => exec("start {$authorUrl}"),
                'Linux' => exec("xdg-open {$authorUrl}"),
            };

            $this->components->info('Thank you so much for considering to sponsor this project 💙');
        }
    }

    protected function publishingConfig(): void
    {
        $this->call('vendor:publish', ['--tag' => 'rest-presenter-config']);
    }

    protected function publishingServiceProvider(): void
    {
        $this->call('vendor:publish', ['--tag' => 'rest-presenter-provider']);

        $providersPath = app()->bootstrapPath('providers.php');
        if (! file_exists($providersPath)) {
            return;
        }

        $callable = [ServiceProvider::class, 'addProviderToBootstrapFile'];
        if (is_callable($callable)) {
            call_user_func($callable, 'App\\Providers\\RESTPresenterServiceProvider', $providersPath);
        }
    }

    protected function publishingDefaultResources(): void
    {
        collect($this->filesystem->directories(__DIR__ . '/../Resources'))
            ->map(fn ($resource) => Str::singular(basename($resource)))
            ->each(fn ($resource) => $this->call('rest-presenter:make-resource', ['name' => $resource]));
    }

    protected function firstTimeSetup(): bool
    {
        return ! $this->filesystem->exists(config('rest-presenter.generator.path'));
    }

    protected function checkForUpdates(): void
    {
        $this->components->info('Checking for updates...');
    }
}
