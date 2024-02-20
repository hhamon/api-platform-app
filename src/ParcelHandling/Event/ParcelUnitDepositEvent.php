<?php

declare(strict_types=1);

namespace App\ParcelHandling\Event;

use App\Entity\ParcelLocker;
use App\Entity\ParcelUnit;
use Symfony\Contracts\EventDispatcher\Event;

abstract class ParcelUnitDepositEvent extends Event
{
    public function __construct(
        private readonly ParcelUnit $parcelUnit,
        private readonly ParcelLocker $parcelLocker,
    ) {
    }

    public function getParcelUnit(): ParcelUnit
    {
        return $this->parcelUnit;
    }

    public function getParcelLocker(): ParcelLocker
    {
        return $this->parcelLocker;
    }
}
