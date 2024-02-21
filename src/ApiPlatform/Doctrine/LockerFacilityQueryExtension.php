<?php

declare(strict_types=1);

namespace App\ApiPlatform\Doctrine;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\LockerFacility;
use Doctrine\ORM\QueryBuilder;

final class LockerFacilityQueryExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        ?Operation $operation = null,
        array $context = []
    ): void {
        $this->filterUncommissionedFacilities($resourceClass, $queryBuilder);
    }

    public function applyToItem(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        array $identifiers,
        ?Operation $operation = null,
        array $context = []
    ): void {
        $this->filterUncommissionedFacilities($resourceClass, $queryBuilder);
    }

    private function filterUncommissionedFacilities(string $resourceClass, QueryBuilder $queryBuilder): void
    {
        if ($resourceClass !== LockerFacility::class) {
            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];

        $queryBuilder->andWhere($queryBuilder->expr()->isNotNull($rootAlias . '.commissionedAt'));
    }
}