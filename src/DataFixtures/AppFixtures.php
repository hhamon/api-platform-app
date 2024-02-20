<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\ParcelLocker;
use App\Factory\LockerFacilityFactory;
use App\Factory\ParcelLockerFactory;
use App\Factory\ParcelUnitFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne([
            'guid' => '819f3196-36aa-45e8-ba66-acaf483d482a',
            'email' => 'admin@example.com',
            'roles' => ['ROLE_ADMIN'],
        ]);

        UserFactory::createOne([
            'email' => 'delivery-man@example.com',
            'roles' => ['ROLE_DELIVERY_MAN'],
        ]);

        UserFactory::createOne([
            'email' => 'customer@example.com',
            'roles' => ['ROLE_CUSTOMER'],
        ]);

        UserFactory::createMany(15);

        $facility1 = LockerFacilityFactory::createOne([
            'name' => 'Edward',
            'commissionedAt' => new \DateTimeImmutable('2023-08-02'),
        ]);

        ParcelLockerFactory::createMany(10, [
            'facility' => $facility1,
            'state' => ParcelLocker::STATE_READY_FOR_USE,
        ]);

        ParcelLockerFactory::createMany(8, [
            'facility' => $facility1,
            'state' => ParcelLocker::STATE_IN_USE,
        ]);

        ParcelLockerFactory::createMany(2, [
            'facility' => $facility1,
            'state' => ParcelLocker::STATE_OUT_OF_ORDER,
        ]);

        LockerFacilityFactory::createOne([
            'name' => 'Juliet',
            'commissionedAt' => new \DateTimeImmutable('2023-07-17'),
        ]);

        LockerFacilityFactory::createOne([
            'name' => 'Gaspard',
            'commissionedAt' => null,
        ]);

        LockerFacilityFactory::createMany(30);
        ParcelLockerFactory::createMany(150);
        ParcelUnitFactory::createMany(83);
    }
}
