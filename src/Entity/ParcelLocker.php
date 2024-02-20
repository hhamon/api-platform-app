<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ParcelLockerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: ParcelLockerRepository::class)]
#[ORM\Index(columns: ['facility_id', 'state', 'size'], name: 'parcel_locker_search_available_at_facility_idx')]
#[ORM\UniqueConstraint(name: 'parcel_locker_facility_serial_unique', columns: ['facility_id', 'serial'])]
class ParcelLocker
{
    public const SIZE_SMALL = 'S';

    public const SIZE_MEDIUM = 'M';

    public const SIZE_LARGE = 'L';

    public const SIZE_XLARGE = 'XL';

    public const SIZES = [
        'Small' => self::SIZE_SMALL,
        'Medium' => self::SIZE_MEDIUM,
        'Large' => self::SIZE_LARGE,
        'Extra Large' => self::SIZE_XLARGE,
    ];

    public const STATE_READY_FOR_USE = 'ready-for-use';

    public const STATE_IN_USE = 'in-use';

    public const STATE_OUT_OF_ORDER = 'out-of-order';

    public const STATES = [
        'Ready for use' => self::STATE_READY_FOR_USE,
        'In use' => self::STATE_IN_USE,
        'Out of order' => self::STATE_OUT_OF_ORDER,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\Column(length: 15)]
    private string $state = self::STATE_READY_FOR_USE;

    #[ORM\Column(length: 6, nullable: true)]
    private ?string $unlockCode = null;

    private ?ParcelUnitDeposit $deposit = null;

    /**
     * @return string[]
     */
    public static function getSuitableSizes(string $minSize): array
    {
        $sizes = \array_values(self::SIZES);
        $offset = \array_search($minSize, $sizes, true);
        \assert(\is_int($offset));

        return \array_splice($sizes, $offset);
    }

    public function __construct(
        #[ORM\ManyToOne(inversedBy: 'parcelLockers')]
        #[ORM\JoinColumn(nullable: false)]
        private readonly LockerFacility $facility,
        #[ORM\Column(length: 4)]
        private readonly string $serial,
        #[ORM\Column(length: 2)]
        private readonly string $size = self::SIZE_SMALL
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSerial(): string
    {
        return $this->serial;
    }

    public function getSize(): string
    {
        return $this->size;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public function getUnlockCode(): string
    {
        return (string) $this->unlockCode;
    }

    public function setUnlockCode(?string $unlockCode): void
    {
        $this->unlockCode = $unlockCode;
    }

    public function getFacility(): ?LockerFacility
    {
        return $this->facility;
    }

    public function getDeposit(): ?ParcelUnitDeposit
    {
        return $this->deposit;
    }

    public function acceptParcelDeposit(ParcelUnitDeposit $deposit, string $unlockCode): void
    {
        if ($this->state !== self::STATE_READY_FOR_USE) {
            throw new \DomainException('Locker is not available for use.');
        }

        $this->state = self::STATE_IN_USE;
        $this->unlockCode = $unlockCode;
        $this->deposit = $deposit;
    }

    public function pickupParcel(string $unlockCode): void
    {
        if ($this->state !== self::STATE_IN_USE) {
            throw new \DomainException('Locker is not currently used.');
        }

        if ($this->unlockCode !== $unlockCode) {
            throw new \DomainException('Invalid unlocked code.');
        }

        $this->unlockCode = null;
        $this->state = self::STATE_READY_FOR_USE;
    }
}
