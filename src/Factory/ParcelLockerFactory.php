<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\ParcelLocker;
use App\ParcelHandling\LockerUnlockCodeGeneratorInterface;
use App\Repository\ParcelLockerRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<ParcelLocker>
 *
 * @method        ParcelLocker|Proxy                     create(array|callable $attributes = [])
 * @method static ParcelLocker|Proxy                     createOne(array $attributes = [])
 * @method static ParcelLocker|Proxy                     find(object|array|mixed $criteria)
 * @method static ParcelLocker|Proxy                     findOrCreate(array $attributes)
 * @method static ParcelLocker|Proxy                     first(string $sortedField = 'id')
 * @method static ParcelLocker|Proxy                     last(string $sortedField = 'id')
 * @method static ParcelLocker|Proxy                     random(array $attributes = [])
 * @method static ParcelLocker|Proxy                     randomOrCreate(array $attributes = [])
 * @method static ParcelLockerRepository|RepositoryProxy repository()
 * @method static ParcelLocker[]|Proxy[]                 all()
 * @method static ParcelLocker[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static ParcelLocker[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static ParcelLocker[]|Proxy[]                 findBy(array $attributes)
 * @method static ParcelLocker[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static ParcelLocker[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class ParcelLockerFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     */
    public function __construct(
        private readonly LockerUnlockCodeGeneratorInterface $unlockCodeGenerator,
    ) {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    protected function getDefaults(): array
    {
        return [
            'facility' => LockerFacilityFactory::random(),
            'serial' => self::faker()->regexify('/^[A-H]\d{3}$/'),
            'size' => self::faker()->randomElement(ParcelLocker::SIZES),
            'state' => self::faker()->randomElement(ParcelLocker::STATES),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            ->afterInstantiate(function (ParcelLocker $parcelLocker): void {
                if ($parcelLocker->getState() === ParcelLocker::STATE_IN_USE) {
                    $parcelLocker->setUnlockCode($this->unlockCodeGenerator->generate());
                }
            })
        ;
    }

    protected static function getClass(): string
    {
        return ParcelLocker::class;
    }
}
