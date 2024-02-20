<?php

declare(strict_types=1);

namespace App\ParcelHandling;

interface LockerUnlockCodeGeneratorInterface
{
    public function generate(): string;
}
