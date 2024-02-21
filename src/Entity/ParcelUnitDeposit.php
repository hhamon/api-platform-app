<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\Post;
use App\ApiPlatform\State\ParcelDepositProcessor;
use App\ParcelHandling\Model\ParcelDeposit;
use App\Repository\ParcelUnitDepositRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Uid\Uuid;

#[Post(
    uriTemplate: '/parcel-unit-deposits',
    denormalizationContext: [
        AbstractNormalizer::GROUPS => ['parcel_deposit:write'],
    ],
    security: 'is_granted("ROLE_DELIVERY_MAN")',
    input: ParcelDeposit::class,
    processor: ParcelDepositProcessor::class,
)]
#[ORM\Entity(repositoryClass: ParcelUnitDepositRepository::class)]
#[ORM\UniqueConstraint(name: 'parcel_unit_deposit_guid_unique', columns: ['guid'])]
class ParcelUnitDeposit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\Column(length: 36)]
    private readonly string $guid;

    public function __construct(
        #[ORM\ManyToOne]
        #[ORM\JoinColumn(nullable: false)]
        private readonly ParcelUnit $parcel,
        #[ORM\ManyToOne]
        #[ORM\JoinColumn(nullable: false)]
        private readonly ParcelLocker $locker,
        #[ORM\Column]
        private /*readonly*/ \DateTimeImmutable $depositedAt,
        ?string $guid = null,
    ) {
        $this->guid = $guid ?: (string) Uuid::v4();
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

    public function getDepositedAt(): \DateTimeImmutable
    {
        return $this->depositedAt;
    }

    public function getFacilityName(): string
    {
        return $this->getFacility()->getName();
    }
}
