<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Strict\Rector\Ternary\DisallowedShortTernaryRuleFixerRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/config',
        __DIR__ . '/public',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withSkip([
        __DIR__ . '/config/bundles.php',
        __DIR__ . '/src/Migrations',
        DisallowedShortTernaryRuleFixerRector::class,
    ])
    // uncomment to reach your current PHP version
    // ->withPhpSets()
    ->withRules([
        AddVoidReturnTypeWhereNoReturnRector::class,
    ])
    ->withAttributesSets(
        symfony: true,
        doctrine: true,
        phpunit: true,
    )
    ->withPhpSets(php83: true)
    ->withImportNames(importShortClasses: false, removeUnusedImports: true)
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        typeDeclarations: true,
        privatization: true,
        instanceOf: true,
        earlyReturn: true,
        strictBooleans: true,
    )
;
