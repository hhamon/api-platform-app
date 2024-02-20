<?php

namespace App\Factory;

use App\Entity\ParcelUnitDeposit;
use App\Repository\ParcelUnitDepositRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<ParcelUnitDeposit>
 *
 * @method        ParcelUnitDeposit|Proxy                     create(array|callable $attributes = [])
 * @method static ParcelUnitDeposit|Proxy                     createOne(array $attributes = [])
 * @method static ParcelUnitDeposit|Proxy                     find(object|array|mixed $criteria)
 * @method static ParcelUnitDeposit|Proxy                     findOrCreate(array $attributes)
 * @method static ParcelUnitDeposit|Proxy                     first(string $sortedField = 'id')
 * @method static ParcelUnitDeposit|Proxy                     last(string $sortedField = 'id')
 * @method static ParcelUnitDeposit|Proxy                     random(array $attributes = [])
 * @method static ParcelUnitDeposit|Proxy                     randomOrCreate(array $attributes = [])
 * @method static ParcelUnitDepositRepository|RepositoryProxy repository()
 * @method static ParcelUnitDeposit[]|Proxy[]                 all()
 * @method static ParcelUnitDeposit[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static ParcelUnitDeposit[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static ParcelUnitDeposit[]|Proxy[]                 findBy(array $attributes)
 * @method static ParcelUnitDeposit[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static ParcelUnitDeposit[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class ParcelUnitDepositFactory extends ModelFactory
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
            'depositedAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'guid' => self::faker()->text(36),
            'locker' => ParcelLockerFactory::new(),
            'parcel' => ParcelUnitFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(ParcelUnitDeposit $parcelUnitDeposit): void {})
        ;
    }

    protected static function getClass(): string
    {
        return ParcelUnitDeposit::class;
    }
}
