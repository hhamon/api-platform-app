<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use Symplify\CodingStandard\Fixer\ArrayNotation\ArrayOpenerAndCloserNewlineFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    ->withPaths([
        __DIR__ . '/config',
        __DIR__ . '/public',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])

    ->withRules([
        NoUnusedImportsFixer::class,
    ])

    ->withSkip([
        ArrayOpenerAndCloserNewlineFixer::class,
    ])

    ->withPreparedSets(
        psr12: true,
        arrays: true,
        comments: true,
        docblocks: true,
        spaces: true,
        namespaces: true,
        controlStructures: true,
        cleanCode: true,
    )

    ->withPhpCsFixerSets(
        doctrineAnnotation: true,
        php74Migration: true,
        php80Migration: true,
        php81Migration: true,
        php82Migration: true,
        php83Migration: true,
        symfony: true,
    )
;
