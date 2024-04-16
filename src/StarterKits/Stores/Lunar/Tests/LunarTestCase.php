<?php

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Tests;

use Cartalyst\Converter\Laravel\ConverterServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Kalnoy\Nestedset\NestedSetServiceProvider;
use Lunar\Base\ModelManifest;
use Lunar\Base\ModelManifestInterface;
use Spatie\Activitylog\ActivitylogServiceProvider;
use Spatie\LaravelBlink\BlinkServiceProvider;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\LunarApiKitServiceProvider;
use XtendPackages\RESTPresenter\Tests\TestCase;

class LunarTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/../../../../../vendor/lunarphp/core/database/migrations');
    }

    protected function getEnvironmentSetUp($app): void
    {
        parent::getEnvironmentSetUp($app);

        $this->registerBlueprintMacros();

        app()->singleton(ModelManifestInterface::class, function ($app) {
            return $app->make(ModelManifest::class);
        });

        $app['config']->set('lunar.database.connection', 'testbench');
        $app['config']->set('lunar.database.table_prefix', 'lunar_');
    }

    protected function getPackageProviders($app): array
    {
        return array_merge(parent::getPackageProviders($app), [
            LunarApiKitServiceProvider::class,
            BlinkServiceProvider::class,
            ActivitylogServiceProvider::class,
            ConverterServiceProvider::class,
            NestedSetServiceProvider::class,
            MediaLibraryServiceProvider::class,
        ]);
    }

    protected function registerBlueprintMacros(): void
    {
        Blueprint::macro('scheduling', function () {
            /** @var Blueprint $this */
            $this->boolean('enabled')->default(false)->index();
            $this->timestamp('starts_at')->nullable()->index();
            $this->timestamp('ends_at')->nullable()->index();
        });

        Blueprint::macro('dimensions', function () {
            /** @var Blueprint $this */
            $columns = ['length', 'width', 'height', 'weight', 'volume'];
            foreach ($columns as $column) {
                $this->decimal("{$column}_value", 10, 4)->default(0)->nullable()->index();
                $this->string("{$column}_unit")->default('mm')->nullable();
            }
        });

        Blueprint::macro('userForeignKey', function ($field_name = 'user_id', $nullable = false) {
            /** @var Blueprint $this */
            $userModel = config('auth.providers.users.model');

            $type = config('lunar.database.users_id_type', 'bigint');

            if ($type == 'uuid') {
                $this->foreignUuId($field_name)
                    ->nullable($nullable)
                    ->constrained(
                        (new $userModel())->getTable() // @phpstan-ignore-line
                    );
            } elseif ($type == 'int') {
                $this->unsignedInteger($field_name)->nullable($nullable);
                $this->foreign($field_name)->references('id')->on('users');
            } else {
                $this->foreignId($field_name)
                    ->nullable($nullable)
                    ->constrained(
                        (new $userModel())->getTable() // @phpstan-ignore-line
                    );
            }
        });
    }
}
