<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ParcelUnitPickupRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ParcelUnitPickupRepository::class)]
#[ORM\UniqueConstraint(name: 'parcel_unit_pickup_guid_unique', columns: ['guid'])]
class ParcelUnitPickup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\Column(length: 36)]
    private readonly string $guid;

    #[ORM\Column]
    private readonly \DateTimeImmutable $pickedUpAt;

    public function __construct(
        #[ORM\ManyToOne]
        #[ORM\JoinColumn(nullable: false)]
        private readonly ParcelUnit $parcel,
        #[ORM\ManyToOne]
        #[ORM\JoinColumn(nullable: false)]
        private readonly ParcelLocker $locker,
        #[ORM\ManyToOne]
        #[ORM\JoinColumn(nullable: false)]
        private readonly User $customer,
        #[ORM\Column(length: 6)]
        private readonly string $unlockCode,
        ?\DateTimeImmutable $pickedUpAt = null,
        ?string $guid = null,
    ) {
        $this->guid = $guid ?: (string) Uuid::v4();
        $this->pickedUpAt = $pickedUpAt ?: new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGuid(): Uuid
    {
        return Uuid::fromString($this->guid);
    }

    public function getParcel(): ParcelUnit
    {
        return $this->parcel;
    }

    public function getFacility(): LockerFacility
    {
        /** @var LockerFacility */
        return $this->locker->getFacility();
    }

    public function getLocker(): ParcelLocker
    {
        return $this->locker;
    }

    public function getCustomer(): User
    {
        return $this->customer;
    }

    public function getPickedUpAt(): \DateTimeImmutable
    {
        return $this->pickedUpAt;
    }

    public function getUnlockCode(): string
    {
        return $this->unlockCode;
    }
}
