<?php

declare(strict_types=1);

namespace App\ParcelHandling\Exception;

final class ParcelUnitNotFoundException extends \DomainException
{
    public static function fromSerial(string $serial, ?\Throwable $previous = null): static
    {
        return new self(
            message: \sprintf('Unable to find parcel unit identified by serial "%s".', $serial),
            previous: $previous,
        );
    }

    public static function fromId(string $id, ?\Throwable $previous = null): static
    {
        return new self(
            message: \sprintf('Unable to find parcel unit identified by ID "%s".', $id),
            previous: $previous,
        );
    }
}
