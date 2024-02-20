<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\LockerFacility;
use App\Pagination\PagerfantaPaginator;
use App\Pagination\PaginationCriteria;
use App\Pagination\PaginatorInterface;
use App\ParcelHandling\Exception\LockerFacilityNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LockerFacility>
 *
 * @method LockerFacility|null find($id, $lockMode = null, $lockVersion = null)
 * @method LockerFacility|null findOneBy(array $criteria, array $orderBy = null)
 * @method LockerFacility[]    findAll()
 * @method LockerFacility[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LockerFacilityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LockerFacility::class);
    }

    /**
     * @return PaginatorInterface<LockerFacility>
     */
    public function paginateForHomepage(int $page, int $limit): PaginatorInterface
    {
        $paginator = PagerfantaPaginator::fromQueryBuilder($this->createCommissionedFacilitiesQueryBuilder());
        $paginator->applyCriteria(new PaginationCriteria($page, $limit));

        /** @var PaginatorInterface<LockerFacility> */
        return $paginator;
    }

    public function getByName(string $canonicalName): LockerFacility
    {
        if (! $facility = $this->findOneBy(['name' => $canonicalName])) {
            throw LockerFacilityNotFoundException::fromCanonicalName($canonicalName);
        }

        return $facility;
    }

    public function findOneCommissionedByName(string $name): ?LockerFacility
    {
        $qb = $this->createCommissionedFacilitiesQueryBuilder();

        $result = $qb->andWhere($qb->expr()->eq($qb->expr()->lower('locker_facility.name'), ':lowered_name'))
            ->setParameter('lowered_name', \mb_strtolower($name))
            ->getQuery()
            ->getOneOrNullResult();

        \assert($result instanceof LockerFacility || $result === null);

        return $result;
    }

    private function createCommissionedFacilitiesQueryBuilder(): QueryBuilder
    {
        $qb = $this->createQueryBuilder('locker_facility');

        return $qb->andWhere($qb->expr()->isNotNull('locker_facility.commissionedAt'))
            ->orderBy('locker_facility.commissionedAt', 'DESC');
    }
}
