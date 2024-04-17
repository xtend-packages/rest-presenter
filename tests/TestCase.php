<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Tests;

use Orchestra\Testbench\TestCase as Orchestra;

/**
 * @internal
 */
class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations(['--database' => 'testbench']);
    }
}
