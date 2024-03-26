<?php

use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Tests\LunarTestCase;
use XtendPackages\RESTPresenter\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

// StarterKits Tests
uses(LunarTestCase::class)->in(__DIR__ . '/../src/StarterKits/Stores/Lunar/Tests');
