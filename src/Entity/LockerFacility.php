<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\LockerFacilityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: LockerFacilityRepository::class)]
#[ORM\UniqueConstraint(name: 'locker_facility_name_unique', columns: ['name'])]
class LockerFacility
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $commissionedAt = null;

    /**
     * @var Collection<int, ParcelLocker>
     */
    #[ORM\OneToMany(mappedBy: 'facility', targetEntity: ParcelLocker::class)]
    #[ORM\OrderBy(['serial' => 'ASC'])]
    private Collection $parcelLockers;

    public function __construct(
        #[ORM\Column(length: 20)]
        #[NotBlank]
        private readonly string $name,
    ) {
        $this->parcelLockers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCommissionedAt(): ?\DateTimeImmutable
    {
        return $this->commissionedAt;
    }

    public function setCommissionedAt(?\DateTimeImmutable $commissionedAt): void
    {
        $this->commissionedAt = $commissionedAt;
    }

    /**
     * @return Collection<int, ParcelLocker>
     */
    public function getParcelLockers(): Collection
    {
        return $this->parcelLockers;
    }

    public function addParcelLocker(ParcelLocker $parcelLocker): void
    {
        if (! $this->parcelLockers->contains($parcelLocker)) {
            $this->parcelLockers->add($parcelLocker);
        }
    }

    public function removeParcelLocker(ParcelLocker $parcelLocker): void
    {
        $this->parcelLockers->removeElement($parcelLocker);
    }

    public function getCanonicalName(): string
    {
        return \mb_strtolower($this->getName());
    }
}
