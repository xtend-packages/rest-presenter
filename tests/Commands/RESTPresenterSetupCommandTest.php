<?php

declare(strict_types=1);

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

beforeEach(function (): void {
    $this->filesystem = app(Filesystem::class);

    if ($this->filesystem->isDirectory(config('rest-presenter.generator.path'))) {
        $this->filesystem->deleteDirectory(config('rest-presenter.generator.path'));
    }
});

describe('RESTPresenterSetupCommand', function (): void {
    test('initial setup', function (): void {
        $this->artisan('rest-presenter:setup')
            ->expectsChoice(
                question: 'Would you like to install any of these starter kits?',
                answer: ['Filament'],
                answers: ['Filament', 'Sanctum'],
            )
            ->assertExitCode(0);
    });

    test('publishing config', function (): void {
        if ($this->filesystem->exists(config_path('rest-presenter.php'))) {
            unlink(config_path('rest-presenter.php'));
        }

        $this->assertFalse($this->filesystem->exists(config_path('rest-presenter.php')));

        $this->artisan('vendor:publish', [
            '--tag' => 'rest-presenter-config',
        ]);

        $this->assertTrue($this->filesystem->exists(config_path('rest-presenter.php')));
    });

    test('publish default resources', function (): void {
        collect(['User', 'DummyPost'])->each(function ($resource): void {
            $resourceKey = Str::of($resource)
                ->kebab()
                ->value();

            $presenters = [$resourceKey => 'xtend'];
            if ($resourceKey === 'user') {
                $presenters['profile'] = 'xtend';
            }

            //if (File::exists(app_path('Api/Resources/'.Str::plural($resource).'/'.Str::singular($resource).'ResourceController.php'))) {
            if ($this->filesystem->exists(app_path('Api/Resources/'.Str::plural($resource).'/'.Str::singular($resource).'ResourceController.php'))) {
                unlink(app_path('Api/Resources/'.Str::plural($resource).'/'.Str::singular($resource).'ResourceController.php'));
            }

            $this->artisan('rest-presenter:make-resource', [
                'model' => 'App\\Models\\'.Str::singular($resource),
                'presenters' => $presenters,
                'name' => $resource,
                'type' => 'new',
            ])->assertExitCode(0);

            $this->assertTrue($this->filesystem->exists(app_path('Api/Resources/'.Str::plural($resource).'/'.Str::singular($resource).'ResourceController.php')));
        });
    });

    test('publish starter kits', function (): void {
        $generatedKitsDirectory = config('rest-presenter.generator.path').'/StarterKits';

        if ($this->filesystem->isDirectory($generatedKitsDirectory)) {
            $this->filesystem->deleteDirectory($generatedKitsDirectory);
        }

        collect(['Auth', 'Filament'])->each(function ($kit): void {
            $this->artisan('rest-presenter:xtend-starter-kit', [
                'name' => $kit,
            ])->assertExitCode(0);
        });
    });

    test('check for updates', function (): void {

        $this->filesystem->ensureDirectoryExists(config('rest-presenter.generator.path'));

        $this->artisan('rest-presenter:setup')
            ->expectsOutputToContain('Checking for updates...')
            ->assertExitCode(0);
    });
})->skip();
