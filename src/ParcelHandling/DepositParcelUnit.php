<?php

declare(strict_types=1);

namespace App\ParcelHandling;

use App\Entity\LockerFacility;
use App\Entity\ParcelUnitDeposit;
use App\Entity\User;
use App\ParcelHandling\Event\ParcelUnitDepositedEvent;
use App\ParcelHandling\Event\ParcelUnitDepositingEvent;
use App\Repository\ParcelUnitDepositRepository;
use App\Repository\ParcelUnitRepository;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class DepositParcelUnit
{
    public function __construct(
        private readonly ParcelUnitRepository $parcelUnitRepository,
        private readonly ParcelUnitDepositRepository $parcelUnitDepositRepository,
        private readonly ParcelLockerLocatorInterface $availableParcelLockerLocator,
        private readonly LockerUnlockCodeGeneratorInterface $lockerUnlockCodeGenerator,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function deposit(LockerFacility $facility, string $parcelSerial, User $depositedBy): ParcelUnitDeposit
    {
        $parcel = $this->parcelUnitRepository->getBySerial($parcelSerial);
        $locker = $this->availableParcelLockerLocator->locate($facility, $parcel);

        $this->eventDispatcher->dispatch(new ParcelUnitDepositingEvent($parcel, $locker));

        $parcelUnitDeposit = new ParcelUnitDeposit(
            parcel: $parcel,
            locker: $locker,
        );

        $locker->acceptParcelDeposit(
            deposit: $parcelUnitDeposit,
            unlockCode: $this->lockerUnlockCodeGenerator->generate(),
        );

        $this->parcelUnitDepositRepository->save($parcelUnitDeposit);

        $this->eventDispatcher->dispatch(new ParcelUnitDepositedEvent($parcel, $locker));

        return $parcelUnitDeposit;
    }
}
