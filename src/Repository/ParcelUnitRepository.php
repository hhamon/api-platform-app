<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ParcelUnit;
use App\ParcelHandling\Exception\ParcelUnitNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ParcelUnit>
 *
 * @method ParcelUnit|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParcelUnit|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParcelUnit[]    findAll()
 * @method ParcelUnit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class ParcelUnitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParcelUnit::class);
    }

    public function serialExists(string $serial): bool
    {
        return $this->count(['serial' => $serial]) === 1;
    }

    public function getBySerial(string $serial): ParcelUnit
    {
        if (! $parcelUnit = $this->findOneBy(['serial' => $serial])) {
            throw ParcelUnitNotFoundException::fromSerial($serial);
        }

        return $parcelUnit;
    }
}
