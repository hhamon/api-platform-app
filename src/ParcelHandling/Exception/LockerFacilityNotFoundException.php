<?php

declare(strict_types=1);

namespace App\ParcelHandling\Exception;

final class LockerFacilityNotFoundException extends \DomainException
{
    public static function fromCanonicalName(string $canonicalName, ?\Throwable $previous = null): static
    {
        return new self(
            message: \sprintf('Unable to find locker faciliy identified by name "%s".', $canonicalName),
            previous: $previous,
        );
    }
}
