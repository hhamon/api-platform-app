<?php

declare(strict_types=1);

namespace App\ParcelHandling\Exception;

final class ParcelLockerNotFoundException extends \DomainException
{
    public static function fromUnlockCode(
        string $facilityName,
        string $unlockCode,
        ?\Throwable $previous = null,
    ): static {
        return new self(
            message: \sprintf(
                'Unable to find in-use locker by unlock code "%s" at facility "%s".',
                $unlockCode,
                $facilityName,
            ),
            previous: $previous,
        );
    }
}
