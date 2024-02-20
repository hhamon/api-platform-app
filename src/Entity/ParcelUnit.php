<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\ParcelUnitRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/parcel-units/{serial}',
            security: 'is_granted("ROLE_DELIVERY_MAN")',
        ),
        new GetCollection(
            uriTemplate: '/parcel-units',
            normalizationContext: [
                AbstractNormalizer::GROUPS => ['parcel_unit:read'],
            ],
            security: 'is_granted("ROLE_DELIVERY_MAN")'
        ),
        new Post(
            uriTemplate: '/parcel-units',
            denormalizationContext: [
                AbstractNormalizer::GROUPS => ['parcel_unit:write:create'],
            ],
            security: 'is_granted("ROLE_ADMIN")',
            validationContext: [
                'groups' => [Constraint::DEFAULT_GROUP, 'parcel_unit:create'],
            ],
        ),
    ],
)]
#[ORM\Entity(repositoryClass: ParcelUnitRepository::class)]
#[ORM\Index(columns: ['size'], name: 'parcel_unit_size_idx')]
#[ORM\UniqueConstraint(name: 'parcel_unit_serial_unique', columns: ['serial'])]
#[UniqueEntity(fields: ['serial'], groups: ['parcel_unit:create'])]
class ParcelUnit
{
    public final const SERIAL_REGEX = '/^[A-Z0-9]{10}$/';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ['unsigned' => true])]
    #[ApiProperty(identifier: false)]
    private ?int $id = null;

    public function __construct(
        #[ORM\Column(length: 10)]
        #[ApiProperty(identifier: true, example: '723GSQV3W2')]
        #[Groups(['parcel_unit:read', 'parcel_unit:write:create'])]
        #[NotBlank(groups: ['parcel_unit:create'])]
        #[Length(min: 10, max: 10, groups: ['parcel_unit:create'])]
        #[Regex(
            pattern: self::SERIAL_REGEX,
            message: 'Parcel unit serial must only contain 10 digits and letters.',
            groups: ['parcel_unit:create']
        )]
        private readonly string $serial,

        #[ORM\Column(length: 2)]
        #[Groups(['parcel_unit:read', 'parcel_unit:write:create'])]
        #[ApiProperty(example: ParcelLocker::SIZE_LARGE)]
        #[NotBlank(groups: ['parcel_unit:create'])]
        #[Choice(choices: ParcelLocker::SIZES, groups: ['parcel_unit:create'])]
        private readonly string $size,

        #[ORM\Column]
        #[Groups(['parcel_unit:read', 'parcel_unit:write:create'])]
        #[ApiProperty(example: 'customer-42@example.com', security: 'is_granted("ROLE_ADMIN")')]
        #[NotBlank]
        #[Email]
        private string $customerEmail,

        #[ORM\Column]
        #[Groups(['parcel_unit:read', 'parcel_unit:write:create'])]
        #[ApiProperty(example: true)]
        private bool $isDamaged = false,
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

    public function setCustomerEmail(string $customerEmail): void
    {
        $this->customerEmail = $customerEmail;
    }

    public function isDamaged(): bool
    {
        return $this->isDamaged;
    }

    public function setIsDamaged(bool $isDamaged): void
    {
        $this->isDamaged = $isDamaged;
    }
}
