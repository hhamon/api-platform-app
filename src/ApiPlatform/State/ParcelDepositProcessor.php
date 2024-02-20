<?php

declare(strict_types=1);

namespace App\ApiPlatform\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\LockerFacility;
use App\Entity\ParcelUnitDeposit;
use App\Entity\User;
use App\ParcelHandling\DepositParcelUnit;
use App\ParcelHandling\Exception\LockerFacilityNotFoundException;
use App\ParcelHandling\Model\ParcelDeposit;
use App\Repository\LockerFacilityRepository;
use App\Repository\ParcelUnitDepositRepository;
use Symfony\Bundle\SecurityBundle\Security;

final class ParcelDepositProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly Security $security,
        private readonly DepositParcelUnit $depositParcelUnit,
        private readonly LockerFacilityRepository $lockerFacilityRepository,
        private readonly ParcelUnitDepositRepository $parcelUnitDepositRepository,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ParcelUnitDeposit
    {
        \assert($data instanceof ParcelDeposit);

        $deliveryOperator = $this->security->getUser();
        \assert($deliveryOperator instanceof User);

        $facility = $this->lockerFacilityRepository->findOneCommissionedByName($data->facilityName);

        if (!$facility instanceof LockerFacility) {
            throw new LockerFacilityNotFoundException();
        }

        $parcelUnitDeposit = $this->depositParcelUnit->deposit($facility, $data->parcelSerial, $deliveryOperator);

        $this->parcelUnitDepositRepository->save($parcelUnitDeposit);

        return $parcelUnitDeposit;

    }
}
