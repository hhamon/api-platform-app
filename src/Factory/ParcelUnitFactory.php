<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\ParcelLocker;
use App\Entity\ParcelUnit;
use App\Repository\ParcelUnitRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<ParcelUnit>
 *
 * @method        ParcelUnit|Proxy                     create(array|callable $attributes = [])
 * @method static ParcelUnit|Proxy                     createOne(array $attributes = [])
 * @method static ParcelUnit|Proxy                     find(object|array|mixed $criteria)
 * @method static ParcelUnit|Proxy                     findOrCreate(array $attributes)
 * @method static ParcelUnit|Proxy                     first(string $sortedField = 'id')
 * @method static ParcelUnit|Proxy                     last(string $sortedField = 'id')
 * @method static ParcelUnit|Proxy                     random(array $attributes = [])
 * @method static ParcelUnit|Proxy                     randomOrCreate(array $attributes = [])
 * @method static ParcelUnitRepository|RepositoryProxy repository()
 * @method static ParcelUnit[]|Proxy[]                 all()
 * @method static ParcelUnit[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static ParcelUnit[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static ParcelUnit[]|Proxy[]                 findBy(array $attributes)
 * @method static ParcelUnit[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static ParcelUnit[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class ParcelUnitFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    protected function getDefaults(): array
    {
        return [
            'customerEmail' => self::faker()->email(),
            'isDamaged' => self::faker()->boolean(5),
            'serial' => self::faker()->unique()->regexify(ParcelUnit::SERIAL_REGEX),
            'size' => self::faker()->randomElement(ParcelLocker::SIZES),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(ParcelUnit $parcelUnit): void {})
        ;
    }

    protected static function getClass(): string
    {
        return ParcelUnit::class;
    }
}
