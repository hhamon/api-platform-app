<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\LockerFacility;
use App\Entity\ParcelUnit;
use App\Entity\ParcelUnitDeposit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ParcelUnitDeposit>
 *
 * @method ParcelUnitDeposit|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParcelUnitDeposit|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParcelUnitDeposit[]    findAll()
 * @method ParcelUnitDeposit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParcelUnitDepositRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParcelUnitDeposit::class);
    }

    public function save(ParcelUnitDeposit $parcelUnitDeposit): void
    {
        $em = $this->getEntityManager();
        $em->persist($parcelUnitDeposit);
        $em->flush();
    }

    public function existsForParcel(ParcelUnit $parcel): bool
    {
        return $this->count(['parcel' => $parcel]) === 1;
    }

    public function existsAtFacility(LockerFacility $facility, ParcelUnit|string $parcelSerial): bool
    {
        if ($parcelSerial instanceof ParcelUnit) {
            $parcelSerial = $parcelSerial->getSerial();
        }

        $qb = $this->createQueryBuilder('pud');

        $query = $qb
            ->select($qb->expr()->count('pud.id'))
            ->innerJoin('pud.parcel', 'parcel')
            ->innerJoin('pud.locker', 'locker')
            ->innerJoin('locker.facility', 'facility')
            ->andWhere($qb->expr()->eq('facility', ':facility'))
            ->andWhere($qb->expr()->eq('parcel.serial', ':serial'))
            ->setParameter('facility', $facility)
            ->setParameter('serial', $parcelSerial)
            ->getQuery();

        $result = $query->getSingleScalarResult();
        \assert(\is_int($result));

        return $result === 1;
    }
}
