<?php

namespace XtendPackages\RESTPresenter\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use UnhandledMatchError;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\multiselect;

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
        }

        $this->publishingConfig();
        $this->publishingServiceProvider();
        $this->publishingDefaultResources();
        $this->publishStarterKits();
    }

    protected function starGitHubRepo(): void
    {
        if (confirm('Please star this project on GitHub 🤩')) {
            $repoUrl = 'https://github.com/xtend-packages/rest-presenter';

            match (PHP_OS_FAMILY) {
                'Darwin' => exec("open {$repoUrl}"),
                'Windows' => exec("start {$repoUrl}"),
                'Linux' => exec("xdg-open {$repoUrl}"),
                default => throw new UnhandledMatchError(PHP_OS_FAMILY),
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
                default => throw new UnhandledMatchError(PHP_OS_FAMILY),
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
            ->each(fn ($resource) => $this->call('rest-presenter:make-resource', [
                'model' => 'App\\Models\\' . Str::singular($resource),
                'name' => $resource,
                'type' => 'new',
            ]));
    }

    protected function publishStarterKits(): void
    {
        $starterKitsDirectory = __DIR__ . '/../StarterKits';
        $generatedKitsDirectory = config('rest-presenter.generator.path') . '/StarterKits';
        $this->filesystem->ensureDirectoryExists($generatedKitsDirectory);

        /** @var \Illuminate\Support\Collection<string, string> $unpublishedStarterKits */
        $unpublishedStarterKits = collect($this->filesystem->allFiles($starterKitsDirectory))
            ->map(fn ($file) => $file->getRelativePathname())
            ->filter(fn ($file) => ! $this->filesystem->exists($generatedKitsDirectory . '/' . $file))
            ->filter(fn ($file) => str_ends_with($file, 'ApiKitServiceProvider.php'))
            ->map(fn ($file) => str_replace('ApiKitServiceProvider.php', '', basename($file)))
            ->map(fn ($kit) => $kit)
            ->values();

        if ($unpublishedStarterKits->isEmpty()) {
            $this->components->info('All starter kits have already been installed');

            return;
        }

        $starterKits = multiselect(
            label: 'Would you like to install any of these starter kits?',
            options: $unpublishedStarterKits->toArray(), // @phpstan-ignore-line
            hint: 'You can re-run this command to install more starter kits later',
        );

        if (! $starterKits) {
            return;
        }

        foreach ($starterKits as $starterKit) {
            $this->call('rest-presenter:xtend-starter-kit', ['name' => $starterKit]);
        }
    }

    protected function firstTimeSetup(): bool
    {
        return ! $this->filesystem->exists(
            path: type(config('rest-presenter.generator.path'))->asString(),
        );
    }

    protected function checkForUpdates(): void
    {
        $this->components->info('Checking for updates...');
    }
}
