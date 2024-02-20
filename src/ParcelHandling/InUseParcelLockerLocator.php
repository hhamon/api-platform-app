<?php

declare(strict_types=1);

namespace App\ParcelHandling;

use App\Entity\LockerFacility;
use App\Entity\ParcelLocker;
use App\Entity\ParcelUnit;
use App\ParcelHandling\Exception\NoSuitableLockerForParcelException;
use App\Repository\ParcelUnitDepositRepository;

final readonly class InUseParcelLockerLocator implements ParcelLockerLocatorInterface
{
    public function __construct(
        private ParcelUnitDepositRepository $parcelUnitDepositRepository,
    ) {
    }

    public function locate(LockerFacility $facility, ParcelUnit $parcelUnit): ParcelLocker
    {
        if (! $deposit = $this->parcelUnitDepositRepository->findOneBy(['parcel' => $parcelUnit])) {
            throw new NoSuitableLockerForParcelException($facility, $parcelUnit);
        }

        return $deposit->getLocker();
    }
}
