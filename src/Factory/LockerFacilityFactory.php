<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\LockerFacility;
use App\Repository\LockerFacilityRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<LockerFacility>
 *
 * @method        LockerFacility|Proxy                     create(array|callable $attributes = [])
 * @method static LockerFacility|Proxy                     createOne(array $attributes = [])
 * @method static LockerFacility|Proxy                     find(object|array|mixed $criteria)
 * @method static LockerFacility|Proxy                     findOrCreate(array $attributes)
 * @method static LockerFacility|Proxy                     first(string $sortedField = 'id')
 * @method static LockerFacility|Proxy                     last(string $sortedField = 'id')
 * @method static LockerFacility|Proxy                     random(array $attributes = [])
 * @method static LockerFacility|Proxy                     randomOrCreate(array $attributes = [])
 * @method static LockerFacilityRepository|RepositoryProxy repository()
 * @method static LockerFacility[]|Proxy[]                 all()
 * @method static LockerFacility[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static LockerFacility[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static LockerFacility[]|Proxy[]                 findBy(array $attributes)
 * @method static LockerFacility[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static LockerFacility[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class LockerFacilityFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        $commissionedAt = null;
        if (self::faker()->boolean(85)) {
            $commissionedAt = self::faker()->dateTimeBetween('2020-06-13', 'today');
            $commissionedAt = \DateTimeImmutable::createFromMutable($commissionedAt);
        }

        return [
            'commissionedAt' => $commissionedAt,
            'name' => \substr(self::faker()->unique()->firstName(), 0, 20),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(LockerFacility $lockerFacility): void {})
        ;
    }

    protected static function getClass(): string
    {
        return LockerFacility::class;
    }
}
