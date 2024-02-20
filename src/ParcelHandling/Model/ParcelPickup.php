<?php

declare(strict_types=1);

namespace App\ParcelHandling\Model;

final class ParcelPickup
{
    public function __construct(
        public readonly string $lockerFacilityCanonicalName,
        public string $parcelSerial = '',
        public string $unlockCode = '',
    ) {
    }
}
