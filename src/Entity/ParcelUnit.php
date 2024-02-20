<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ParcelUnitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParcelUnitRepository::class)]
#[ORM\Index(columns: ['size'], name: 'parcel_unit_size_idx')]
#[ORM\UniqueConstraint(name: 'parcel_unit_serial_unique', columns: ['serial'])]
class ParcelUnit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ['unsigned' => true])]
    private ?int $id = null;

    public function __construct(
        #[ORM\Column(length: 10)]
        private readonly string $serial,
        #[ORM\Column(length: 2)]
        private readonly string $size,
        #[ORM\Column]
        private readonly string $customerEmail,
        #[ORM\Column]
        private readonly bool $isDamaged,
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

    public function getCustomerEmail(): string
    {
        return $this->customerEmail;
    }

    public function isDamaged(): bool
    {
        return $this->isDamaged;
    }
}
