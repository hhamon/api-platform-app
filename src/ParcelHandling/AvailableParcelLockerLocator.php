<?php

declare(strict_types=1);

namespace App\ParcelHandling;

use App\Entity\LockerFacility;
use App\Entity\ParcelLocker;
use App\Entity\ParcelUnit;
use App\ParcelHandling\Exception\NoSuitableLockerForParcelException;
use App\Repository\ParcelLockerRepository;

final readonly class AvailableParcelLockerLocator implements ParcelLockerLocatorInterface
{
    public function __construct(
        private ParcelLockerRepository $parcelLockerRepository,
    ) {
    }

    public function locate(LockerFacility $facility, ParcelUnit $parcelUnit): ParcelLocker
    {
        foreach (ParcelLocker::getSuitableSizes($parcelUnit->getSize()) as $size) {
            if (($locker = $this->parcelLockerRepository->findAvailableLockerForSize($facility, $size)) instanceof ParcelLocker) {
                return $locker;
            }
        }

        throw new NoSuitableLockerForParcelException($facility, $parcelUnit);
    }
}
