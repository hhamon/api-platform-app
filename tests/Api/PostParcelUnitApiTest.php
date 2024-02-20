<?php

declare(strict_types=1);

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Factory\LockerFacilityFactory;
use App\Factory\ParcelUnitFactory;
use App\Factory\UserFactory;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class PostParcelUnitApiTest extends ApiTestCase
{
    use HasBrowser;
    use ResetDatabase;
    use Factories;

    public function testRecordNewParcelUnitInSystem(): void
    {
        $admin = UserFactory::createOne([
            'guid' => '819f3196-36aa-45e8-ba66-acaf483d482a',
            'email' => 'admin@example.com',
            'roles' => ['ROLE_ADMIN'],
        ]);

        $this->browser()
            ->actingAs($admin)
            ->post('/api/parcel-units', [
                'headers' => [
                    'accept' => 'application/ld+json',
                ],
                'json' => [
                    'serial' => '2837BS1323',
                    'size' => 'M',
                    'customerEmail' => 'customer-101@example.com',
                ],
            ])
            ->assertStatus(201)
            ->assertJson()
            ->assertJson()
            ->assertJsonMatches('"@context"', '/api/contexts/ParcelUnit')
            ->assertJsonMatches('"@id"', '/api/parcel-units/2837BS1323')
            ->assertJsonMatches('"@type"', 'ParcelUnit')
            ->assertJsonMatches('"serial"', '2837BS1323')
            ->assertJsonMatches('"size"', 'M')
            ->assertJsonMatches('"customerEmail"', 'customer-101@example.com')
            ->assertJsonMatches('"damaged"', false);

        ParcelUnitFactory::assert()->count(1);
    }

    public function testCreateNewParcelUnitFailsWithValidationErrors(): void
    {
        $admin = UserFactory::createOne([
            'guid' => '819f3196-36aa-45e8-ba66-acaf483d482a',
            'email' => 'admin@example.com',
            'roles' => ['ROLE_ADMIN'],
        ]);

        $this->browser()
            ->actingAs($admin)
            ->post('/api/parcel-units', [
                'headers' => [
                    'accept' => 'application/ld+json',
                ],
                'json' => [
                    'serial' => 'FOO',
                    'size' => 'LL',
                    'customerEmail' => '',
                ],
            ])
            ->assertStatus(422)
            ->assertJson()
            ->assertJsonMatches('"@type"', 'ConstraintViolationList')
            ->assertJsonMatches('length("violations")', 4)
            ->assertJsonMatches('"violations"[0]."code"', Length::NOT_EQUAL_LENGTH_ERROR)
            ->assertJsonMatches('"violations"[1]."code"', Regex::REGEX_FAILED_ERROR)
            ->assertJsonMatches('"violations"[2]."code"', Choice::NO_SUCH_CHOICE_ERROR)
            ->assertJsonMatches('"violations"[3]."code"', NotBlank::IS_BLANK_ERROR);

        ParcelUnitFactory::assert()->count(0);
    }

    public function testCreateNewParcelUnitAsCustomerIsForbidden(): void
    {
        $customer = UserFactory::createOne([
            'email' => 'customer@example.com',
            'roles' => ['ROLE_CUSTOMER'],
        ]);

        $this->browser()
            ->actingAs($customer)
            ->post('/api/parcel-units', [
                'headers' => [
                    'accept' => 'application/ld+json',
                ],
                'json' => [
                    'serial' => '2837BS1323',
                    'size' => 'M',
                    'customerEmail' => 'customer-101@example.com',
                ],
            ])
            ->assertStatus(403);

        ParcelUnitFactory::assert()->count(0);
    }
}
