<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\ParcelLocker;
use App\Factory\LockerFacilityFactory;
use App\Factory\ParcelLockerFactory;
use App\Factory\ParcelUnitDepositFactory;
use App\Factory\ParcelUnitFactory;
use App\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class DepositParcelUnitControllerTest extends KernelTestCase
{
    use HasBrowser;
    use ResetDatabase;
    use Factories;

    public function testDepositParcelAtFacility(): void
    {
        $facility = LockerFacilityFactory::createOne([
            'name' => 'Paris',
            'commissionedAt' => new \DateTimeImmutable('2023-08-10'),
        ]);

        ParcelLockerFactory::createOne([
            'facility' => $facility,
            'serial' => 'C001',
            'size' => 'M',
            'state' => ParcelLocker::STATE_READY_FOR_USE,
        ]);

        ParcelUnitFactory::createOne([
            'serial' => '2JD5W0T1IZ',
            'size' => 'M',
        ]);

        UserFactory::createOne([
            'email' => 'deliver@example.com',
            'roles' => ['ROLE_DELIVERY_MAN'],
        ]);

        ParcelUnitDepositFactory::assert()->count(0);

        $this->browser()
            ->visit('/')
            ->click('Paris')
            ->assertSee('C001')
            ->assertSee('ready-for-use')
            ->click('#parcel-deposit-link')
            ->fillField('_username', 'deliver@example.com')
            ->fillField('_password', 'password2023')
            ->click('Sign in')
            ->fillField('parcel_deposit[parcelSerial]', '2JD5W0T1IZ')
            ->click('Deposit this parcel')
            ->assertSee('in-use');

        ParcelUnitDepositFactory::assert()->count(1);
    }
}
