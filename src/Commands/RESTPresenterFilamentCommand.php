<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Attribute\AsCommand;
use XtendPackages\RESTPresenter\Concerns\InteractsWithGit;

use function Laravel\Prompts\confirm;

#[AsCommand(name: 'rest-presenter:filament')]
final class RESTPresenterFilamentCommand extends Command
{
    use InteractsWithGit;

    protected $signature = 'rest-presenter:filament
        {--install : Install REST Presenter for Filament}
        {--uninstall : Uninstall REST Presenter for Filament}';

    protected $description = 'REST Presenter for Filament';

    public function __construct(protected Filesystem $filesystem)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        if ($this->option('install')) {
            return $this->install();
        }

        if (! $this->filesystem->exists(config('rest-presenter.generator.path').'/StarterKits')) {
            $this->components->warn('REST Presenter for Filament is not currently installed.');

            return self::FAILURE;
        }

        if ($this->option('uninstall')) {
            return $this->uninstall();
        }

        $this->components->warn('Please specify an option: --install, --uninstall');

        return self::FAILURE;
    }

    private function install(): int
    {
        $this->components->info('Installing REST Presenter for Filament');

        if (confirm(__('Would you like to auto-commit all changes made by the installer?'))) {
            $this->gitAutoCommit = $this->isCleanWorkingDirectory();
        }

        $this->call('rest-presenter:xtend-starter-kit', ['name' => 'Sanctum']);
        if ($this->gitAutoCommit) {
            $this->commitChanges('feat: REST Presenter Sanctum Starter Kit');
        }

        $this->call('rest-presenter:xtend-starter-kit', ['name' => 'Filament']);
        if ($this->gitAutoCommit) {
            $this->commitChanges('feat: REST Presenter Filament Starter Kit');
        }

        $this->components->info('REST Presenter Filament installed successfully 🚀');

        $this->components->info('Next step when your ready run "php artisan rest-presenter:generate-api-collection" to auto-generate your API collection for Insomnia or Postman.');

        return self::SUCCESS;
    }

    private function uninstall(): int
    {
        if (! confirm('Are you sure you want to uninstall REST Presenter for Filament?')) {
            return self::FAILURE;
        }

        if (confirm(__('Would you like to auto-commit revert changes made by the installer?'))) {
            $this->gitAutoCommit = $this->isCleanWorkingDirectory();
        }

        $this->filesystem->delete(config_path('rest-presenter.php'));
        $this->filesystem->deleteDirectory(config('rest-presenter.generator.path').'/StarterKits');
        $this->filesystem->deleteDirectory(app()->basePath('tests/StarterKits'));
        $this->filesystem->deleteDirectory(resource_path('rest-presenter'));

        if ($this->gitAutoCommit) {
            $this->commitChanges('revert: Remove REST Presenter Sanctum & Filament Starter Kits');
        }

        $this->components->info('REST Presenter Filament uninstalled successfully.');

        return self::SUCCESS;
    }
}
