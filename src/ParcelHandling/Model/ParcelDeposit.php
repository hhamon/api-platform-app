<?php

declare(strict_types=1);

namespace App\ParcelHandling\Model;

use ApiPlatform\Metadata\ApiProperty;
use App\Entity\ParcelLocker;
use App\Entity\ParcelUnit;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

#[GroupSequence(groups: ['ParcelDeposit', 'Length', 'Format', 'BusinessLogic'])]
final class ParcelDeposit
{
    #[NotBlank(message: 'The parcel serial is required.')]
    #[Length(min: 10, max: 10, exactMessage: 'The parcel serial must contain {{ limit }} characters.', groups: ['Length'])]
    #[Regex(pattern: ParcelUnit::SERIAL_REGEX, message: 'The parcel serial number must only contain capital letters and digits.', groups: ['Format'])]
    #[Groups(['parcel_deposit:write'])]
    #[ApiProperty(example: '283YEB23R2')]
    public string $parcelSerial = '';

    #[Choice(choices: ParcelLocker::SIZES)]
    #[Groups(['parcel_deposit:write'])]
    #[ApiProperty(example: ParcelLocker::SIZE_MEDIUM)]
    public ?string $preferredLockerSize = null;

    #[Length(max: 2_000, maxMessage: 'The internal notes must not exceed {{ limit }} characters.')]
    #[Groups(['parcel_deposit:write'])]
    #[ApiProperty(example: 'The parcel unit got damaged by the carrier.')]
    public ?string $internalNotes = null;

    #[Groups(['parcel_deposit:write'])]
    #[ApiProperty(example: 'paris')]
    public ?string $facilityName = null;
}
