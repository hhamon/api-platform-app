<?php

namespace App\Factory;

use App\Entity\ParcelUnitPickup;
use App\Repository\ParcelUnitPickupRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<ParcelUnitPickup>
 *
 * @method        ParcelUnitPickup|Proxy                     create(array|callable $attributes = [])
 * @method static ParcelUnitPickup|Proxy                     createOne(array $attributes = [])
 * @method static ParcelUnitPickup|Proxy                     find(object|array|mixed $criteria)
 * @method static ParcelUnitPickup|Proxy                     findOrCreate(array $attributes)
 * @method static ParcelUnitPickup|Proxy                     first(string $sortedField = 'id')
 * @method static ParcelUnitPickup|Proxy                     last(string $sortedField = 'id')
 * @method static ParcelUnitPickup|Proxy                     random(array $attributes = [])
 * @method static ParcelUnitPickup|Proxy                     randomOrCreate(array $attributes = [])
 * @method static ParcelUnitPickupRepository|RepositoryProxy repository()
 * @method static ParcelUnitPickup[]|Proxy[]                 all()
 * @method static ParcelUnitPickup[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static ParcelUnitPickup[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static ParcelUnitPickup[]|Proxy[]                 findBy(array $attributes)
 * @method static ParcelUnitPickup[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static ParcelUnitPickup[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class ParcelUnitPickupFactory extends ModelFactory
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
        return [
            'customer' => UserFactory::new(),
            'guid' => self::faker()->unique()->uuid(),
            'locker' => ParcelLockerFactory::new(),
            'parcel' => ParcelUnitFactory::new(),
            'pickedUpAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'unlockCode' => self::faker()->text(6),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(ParcelUnitPickup $parcelUnitPickup): void {})
        ;
    }

    protected static function getClass(): string
    {
        return ParcelUnitPickup::class;
    }
}
