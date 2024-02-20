<?php

declare(strict_types=1);

namespace App\ParcelHandling;

final class LockerUnlockCodeGenerator implements LockerUnlockCodeGeneratorInterface
{
    public function generate(): string
    {
        return \substr(\strtoupper(\sha1(\uniqid())), 0, 6);
    }
}
