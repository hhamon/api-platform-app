<?php

declare(strict_types=1);

namespace App\ParcelHandling;

use App\Entity\LockerFacility;
use App\Entity\ParcelLocker;
use App\Entity\ParcelUnit;

interface ParcelLockerLocatorInterface
{
    public function locate(LockerFacility $facility, ParcelUnit $parcelUnit): ParcelLocker;
}
