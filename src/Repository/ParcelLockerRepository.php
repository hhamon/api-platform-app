<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\LockerFacility;
use App\Entity\ParcelLocker;
use App\ParcelHandling\Exception\ParcelLockerNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ParcelLocker>
 *
 * @method ParcelLocker|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParcelLocker|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParcelLocker[]    findAll()
 * @method ParcelLocker[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParcelLockerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParcelLocker::class);
    }

    /**
     * @return ParcelLocker[]
     */
    public function findByFacility(LockerFacility $facility): array
    {
        return $this->findBy(['facility' => $facility], orderBy: ['serial' => 'ASC']);
    }

    public function findAvailableLockerForSize(LockerFacility $facility, string $size): ?ParcelLocker
    {
        $qb = $this->createQueryBuilder('parcel_locker');

        $result = $qb->andWhere($qb->expr()->eq('parcel_locker.facility', ':facility'))
            ->andWhere($qb->expr()->eq('parcel_locker.state', ':state'))
            ->andWhere($qb->expr()->eq('parcel_locker.size', ':size'))
            ->getQuery()
            ->setParameter('facility', $facility)
            ->setParameter('size', $size)
            ->setParameter('state', ParcelLocker::STATE_READY_FOR_USE)
            ->setMaxResults(1)
            ->getOneOrNullResult();

        \assert($result instanceof ParcelLocker || $result === null);

        return $result;
    }

    public function getInUseAtFacilityByUnlockCode(LockerFacility $facility, string $unlockCode): ParcelLocker
    {
        $result = $this->findOneBy([
            'facility' => $facility,
            'unlockCode' => $unlockCode,
            'state' => ParcelLocker::STATE_IN_USE,
        ]);

        if (! $result instanceof ParcelLocker) {
            throw ParcelLockerNotFoundException::fromUnlockCode($facility->getName(), $unlockCode);
        }

        return $result;
    }
}
