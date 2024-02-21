<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Doctrine\Common\Filter\DateFilterInterface;
use ApiPlatform\Doctrine\Common\Filter\SearchFilterInterface;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\OpenApi\Model\Operation as OpenApiOperation;
use ApiPlatform\Serializer\Filter\PropertyFilter;
use App\Repository\LockerFacilityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Context;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ApiResource(
    description: 'List of locker facilities resources',
    operations: [
        new Get(
            uriTemplate: '/facilities/{name}',
            openapi: new OpenApiOperation(
                summary: 'Get a single locker facility resource by its primary identifier.',
                description: 'Get a single `LockerFacility` resource by its primary identifier.',
            ),
            description: 'Get a single locker facility resource by its primary identifier.',
            normalizationContext: [
                AbstractNormalizer::GROUPS => ['locker_facility:read', 'locker_facility:read:item'],
            ],
        ),
        new GetCollection(
            uriTemplate: '/facilities',
            formats: [
                'jsonld',
                'json',
                'csv' => 'text/csv',
            ],
            paginationItemsPerPage: 12,
            paginationMaximumItemsPerPage: 100,
            paginationClientEnabled: true,
            description: 'Get a paginated collection of locker facilities.',

        ),
    ],
    normalizationContext: [
        AbstractNormalizer::GROUPS => ['locker_facility:read'],
    ],
)]
#[ApiFilter(PropertyFilter::class)]
#[ORM\Entity(repositoryClass: LockerFacilityRepository::class)]
#[ORM\UniqueConstraint(name: 'locker_facility_name_unique', columns: ['name'])]
class LockerFacility
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ['unsigned' => true])]
    #[ApiProperty(identifier: false)]
    private ?int $id = null;

    /**
     * The date of commissioning of this facility.
     */
    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    #[Groups(['locker_facility:read'])]
    #[Context([DateTimeNormalizer::FORMAT_KEY => 'Y-m-d'])]
    #[ApiProperty(example: '2023-08-02')]
    #[ApiFilter(DateFilter::class, strategy: DateFilterInterface::EXCLUDE_NULL)]
    private ?\DateTimeImmutable $commissionedAt = null;

    /**
     * @var Collection<int, ParcelLocker>
     */
    #[ORM\OneToMany(mappedBy: 'facility', targetEntity: ParcelLocker::class)]
    #[ORM\OrderBy(['serial' => 'ASC'])]
    #[Groups(['locker_facility:read:item'])]
    private Collection $parcelLockers;

    public function __construct(
        #[ORM\Column(length: 20)]
        #[NotBlank]
        #[ApiProperty(identifier: true)]
        #[ApiFilter(SearchFilter::class, strategy: SearchFilterInterface::STRATEGY_PARTIAL)]
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

    #[Groups(['locker_facility:read'])]
    #[SerializedName('name')]
    #[ApiProperty(example: 'paris')]
    public function getCanonicalName(): string
    {
        return \mb_strtolower($this->getName());
    }
}
