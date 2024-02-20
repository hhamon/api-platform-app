<?php

declare(strict_types=1);

namespace App\ParcelHandling\Exception;

use App\Entity\LockerFacility;
use App\Entity\ParcelUnit;

final class NoSuitableLockerForParcelException extends \DomainException
{
    public function __construct(
        private readonly LockerFacility $lockerFacility,
        private readonly ParcelUnit $parcelUnit,
        ?\Throwable $previous = null,
    ) {
        parent::__construct(
            \sprintf(
                'No available lockers found at facility "%s" to accommodate a "%s" parcel unit.',
                $lockerFacility->getName(),
                $parcelUnit->getSize(),
            ),
            previous: $previous,
        );
    }

    public function getLockerFacility(): LockerFacility
    {
        return $this->lockerFacility;
    }

    public function getParcelUnit(): ParcelUnit
    {
        return $this->parcelUnit;
    }
}
