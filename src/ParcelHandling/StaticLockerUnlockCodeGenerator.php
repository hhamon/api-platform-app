<?php

declare(strict_types=1);

namespace App\ParcelHandling;

/**
 * This class is mainly meant for testing purpose.
 */
final class StaticLockerUnlockCodeGenerator implements LockerUnlockCodeGeneratorInterface
{
    public function generate(): string
    {
        return 'ABCDEF';
    }
}
