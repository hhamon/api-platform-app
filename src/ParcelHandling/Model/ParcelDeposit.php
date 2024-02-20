<?php

declare(strict_types=1);

namespace App\ParcelHandling\Model;

final class ParcelDeposit
{
    public string $parcelSerial = '';

    public ?string $preferredLockerSize = null;

    public ?string $internalNotes = null;

    public ?string $facilityName = null;
}
