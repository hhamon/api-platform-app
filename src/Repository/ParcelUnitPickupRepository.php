<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ParcelUnit;
use App\Entity\ParcelUnitPickup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<ParcelUnitPickup>
 *
 * @method ParcelUnitPickup|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParcelUnitPickup|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParcelUnitPickup[]    findAll()
 * @method ParcelUnitPickup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParcelUnitPickupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParcelUnitPickup::class);
    }

    public function save(ParcelUnitPickup $parcelUnitPickup): void
    {
        $em = $this->getEntityManager();
        $em->persist($parcelUnitPickup);
        $em->flush();
    }

    public function exists(ParcelUnit|string $parcelSerial): bool
    {
        if ($parcelSerial instanceof ParcelUnit) {
            $parcelSerial = $parcelSerial->getSerial();
        }

        $qb = $this->createQueryBuilder('pup');

        $query = $qb
            ->select($qb->expr()->count('pup.id'))
            ->innerJoin('pup.parcel', 'parcel')
            ->andWhere($qb->expr()->eq('parcel.serial', ':serial'))
            ->setParameter('serial', $parcelSerial)
            ->getQuery();

        $result = $query->getSingleScalarResult();
        \assert(\is_int($result));

        return $result === 1;
    }

    public function findOneByGuid(Uuid|string $guid): ?ParcelUnitPickup
    {
        return $this->findOneBy(['guid' => (string) $guid]);
    }
}
