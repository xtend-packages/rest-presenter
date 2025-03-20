<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Concat\JoinStringConcatRector;
use Rector\Config\RectorConfig;
use Rector\Strict\Rector\BooleanNot\BooleanInBooleanNotRuleFixerRector;
use RectorLaravel\Set\LaravelSetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/src',
        __DIR__.'/tests',
    ])
    ->withSkip([
        BooleanInBooleanNotRuleFixerRector::class,
        JoinStringConcatRector::class,
    ])
    ->withAttributesSets(
        symfony: true,
        doctrine: true,
    )
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        typeDeclarations: true,
        privatization: true,
        earlyReturn: true,
        strictBooleans: true,
    )
    ->withSets([
        LaravelSetList::LARAVEL_110,
    ])
    ->withPhpSets();
