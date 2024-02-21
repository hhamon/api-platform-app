<?php

declare(strict_types=1);

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\ParcelLocker;
use App\Factory\LockerFacilityFactory;
use App\Factory\ParcelLockerFactory;
use App\Factory\ParcelUnitDepositFactory;
use App\Factory\ParcelUnitFactory;
use App\Factory\UserFactory;
use DateTimeImmutable;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class PostParcelUnitDepositApiTest extends ApiTestCase
{
    private const PARCEL_UNIT_DEPOSIT_CREATE_PAYLOAD = [
        'parcelSerial' => '2837BS1323',
        'preferredLockerSize' => 'M',
        'internalNotes' => 'Parcel was damaged by the carrier.',
        'facilityName' => 'paris',
    ];

    use HasBrowser;
    use ResetDatabase;
    use Factories;

    protected function setUp(): void
    {
        parent::setUp();

        $facility = LockerFacilityFactory::createOne([
            'name' => 'Paris',
            'commissionedAt' => new DateTimeImmutable('2023-02-12'),
        ]);

        ParcelLockerFactory::createOne([
            'facility' => $facility,
            'size' => ParcelLocker::SIZE_MEDIUM,
            'state' => ParcelLocker::STATE_READY_FOR_USE,
        ]);

        ParcelUnitFactory::createOne([
            'serial' => '2837BS1323',
            'size' => ParcelLocker::SIZE_MEDIUM,
        ]);
    }

    public function testAnonymousUserCannotDepositParcelUnit(): void
    {
        $this->browser()
            ->assertNotAuthenticated()
            ->post('/api/parcel-unit-deposits', [
                'headers' => [
                    'accept' => 'application/ld+json',
                ],
                'json' => self::PARCEL_UNIT_DEPOSIT_CREATE_PAYLOAD,
            ])
            ->assertStatus(401);
    }

    public function testDeliveryOperatorCanDepositParcelUnit(): void
    {
        $this->browser()
            ->actingAs(UserFactory::createOne(['roles' => ['ROLE_DELIVERY_MAN']]))
            ->post('/api/parcel-unit-deposits', [
                'headers' => [
                    'accept' => 'application/ld+json',
                ],
                'json' => self::PARCEL_UNIT_DEPOSIT_CREATE_PAYLOAD,
            ])
            ->assertStatus(201)
            ->assertJson()
            ->assertJsonMatches('"@context"', '/api/contexts/ParcelUnitDeposit')
            ->assertJsonMatches('"@type"', 'ParcelUnitDeposit')
            ->assertJsonMatches('"parcel"', '/api/parcel-units/2837BS1323')
            ->assertJsonMatches('"facility"', '/api/facilities/Paris');

        ParcelUnitDepositFactory::assert()->count(1);
    }

    public function testCustomerCannotDepositParcelUnit(): void
    {
        $this->browser()
            ->actingAs(UserFactory::createOne(['roles' => ['ROLE_CUSTOMER']]))
            ->post('/api/parcel-unit-deposits', [
                'headers' => [
                    'accept' => 'application/ld+json',
                ],
                'json' => self::PARCEL_UNIT_DEPOSIT_CREATE_PAYLOAD,
            ])
            ->assertStatus(403)
            ->assertJson();
    }
}
