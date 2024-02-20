<?php

declare(strict_types=1);

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Factory\LockerFacilityFactory;
use DateTimeImmutable;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class GetFacilitiesApiTest extends ApiTestCase
{
    use HasBrowser;
    use ResetDatabase;
    use Factories;

    public function testGetFacilitiesCollection(): void
    {
        LockerFacilityFactory::createOne([
            'name' => 'Montreal',
            'commissionedAt' => new DateTimeImmutable('2023-08-10'),
        ]);

        LockerFacilityFactory::createOne([
            'name' => 'Geneva',
            'commissionedAt' => null,
        ]);

        LockerFacilityFactory::createOne([
            'name' => 'Paris',
            'commissionedAt' => new DateTimeImmutable('2023-08-16'),
        ]);

        $this->browser()
            ->get('/api/facilities')
            ->assertStatus(200)
            ->assertJson()
            ->assertJsonMatches('"@context"', '/api/contexts/LockerFacility')
            ->assertJsonMatches('"@id"', '/api/facilities')
            ->assertJsonMatches('"@type"', 'hydra:Collection')
            ->assertJsonMatches('"hydra:totalItems"', 2)
            ->assertJsonMatches('"hydra:member"[0]."@id"', '/api/facilities/Montreal')
            ->assertJsonMatches('"hydra:member"[0]."commissionedAt"', '2023-08-10')
            ->assertJsonMatches('"hydra:member"[1]."@id"', '/api/facilities/Paris')
            ->assertJsonMatches('"hydra:member"[1]."commissionedAt"', '2023-08-16');
    }

    public function testGetFacilitiesAsCsv(): void
    {
        LockerFacilityFactory::createOne([
            'name' => 'Montreal',
            'commissionedAt' => new DateTimeImmutable('2023-08-10'),
        ]);

        LockerFacilityFactory::createOne([
            'name' => 'Geneva',
            'commissionedAt' => null,
        ]);

        LockerFacilityFactory::createOne([
            'name' => 'Paris',
            'commissionedAt' => new DateTimeImmutable('2023-08-16'),
        ]);

        $this->browser()
            ->get('/api/facilities', [
                'headers' => [
                    'Accept' => 'text/csv',
                ],
            ])
            ->assertStatus(200)
            ->assertHeaderEquals('Content-Type', 'text/csv; charset=utf-8')
            ->assertContains('commissionedAt,name')
            ->assertContains('2023-08-10,Montreal')
            ->assertContains('2023-08-16,Paris');
    }

    public function testGetSingleFacilityResource(): void
    {
        LockerFacilityFactory::createOne([
            'name' => 'Montreal',
            'commissionedAt' => new DateTimeImmutable('2023-08-10'),
        ]);

        $this->browser()
            ->get('/api/facilities/montreal')
            ->assertStatus(200)
            ->assertJson()
            ->assertJsonMatches('"@context"', '/api/contexts/LockerFacility')
            ->assertJsonMatches('"@id"', '/api/facilities/Montreal')
            ->assertJsonMatches('"name"', 'montreal')
            ->assertJsonMatches('"commissionedAt"', '2023-08-10');
    }

    public function testGetSingleFacilityDefaultsTo404(): void
    {
        $this->browser()
            ->get('/api/facilities/montreal')
            ->assertStatus(404)
            ->assertJson()
            ->assertJsonMatches('"@id"', '/api/errors/404');
    }

    public function testGetSingleUncommissionedFacilityDefaultsTo404(): void
    {
        LockerFacilityFactory::createOne([
            'name' => 'Montreal',
            'commissionedAt' => null,
        ]);

        $this->browser()
            ->get('/api/facilities/montreal')
            ->assertStatus(404)
            ->assertJson()
            ->assertJsonMatches('"@id"', '/api/errors/404');
    }
}
