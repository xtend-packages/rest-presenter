<?php

declare(strict_types=1);

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
final class RESTPresenterSetupCommand extends Command
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

    private function initialSetup(): void
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

    private function starGitHubRepo(): void
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

    private function sponsorThisProject(): void
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

    private function publishingConfig(): void
    {
        $this->call('vendor:publish', ['--tag' => 'rest-presenter-config']);
    }

    private function publishingServiceProvider(): void
    {
        $this->call('vendor:publish', ['--tag' => 'rest-presenter-provider']);

        $providersPath = app()->bootstrapPath('providers.php');
        if (! file_exists($providersPath)) {
            return;
        }

        $callable = [ServiceProvider::class, 'addProviderToBootstrapFile'];
        call_user_func($callable, 'App\\Providers\\RESTPresenterServiceProvider', $providersPath);
    }

    private function publishingDefaultResources(): void
    {
        collect($this->filesystem->directories(__DIR__.'/../Resources'))
            ->map(fn ($resource) => Str::singular(basename((string) $resource)))
            ->each(fn ($resource) => $this->call('rest-presenter:make-resource', [
                'model' => 'App\\Models\\'.Str::singular($resource),
                'name' => $resource,
                'type' => 'new',
            ]));
    }

    private function publishStarterKits(): void
    {
        $starterKitsDirectory = __DIR__.'/../StarterKits';
        $generatedKitsDirectory = config('rest-presenter.generator.path').'/StarterKits';
        $this->filesystem->ensureDirectoryExists($generatedKitsDirectory);

        /** @var \Illuminate\Support\Collection<string, string> $unpublishedStarterKits */
        $unpublishedStarterKits = collect($this->filesystem->allFiles($starterKitsDirectory))
            ->map(fn ($file): string => $file->getRelativePathname())
            ->filter(fn ($file): bool => ! $this->filesystem->exists($generatedKitsDirectory.'/'.$file))
            ->filter(fn ($file): bool => str_ends_with($file, 'ApiKitServiceProvider.php'))
            ->map(fn ($file): string => str_replace('ApiKitServiceProvider.php', '', basename($file)))
            ->map(fn ($kit): string => $kit)
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

        if ($starterKits === []) {
            return;
        }

        foreach ($starterKits as $starterKit) {
            $this->call('rest-presenter:xtend-starter-kit', ['name' => $starterKit]);
        }
    }

    private function firstTimeSetup(): bool
    {
        return ! $this->filesystem->exists(
            path: type(config('rest-presenter.generator.path'))->asString(),
        );
    }

    private function checkForUpdates(): void
    {
        $this->components->info('Checking for updates...');
    }
}
