<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\LockerFacility;
use App\Entity\ParcelUnitPickup;
use App\Entity\User;
use App\ParcelHandling\Form\ParcelPickupType;
use App\ParcelHandling\Model\ParcelPickup;
use App\Repository\ParcelLockerRepository;
use App\Repository\ParcelUnitPickupRepository;
use App\Repository\ParcelUnitRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class PickupParcelController extends AbstractController
{
    public function __construct(
        private readonly ParcelLockerRepository $parcelLockerRepository,
        private readonly ParcelUnitRepository $parcelUnitRepository,
        private readonly ParcelUnitPickupRepository $parcelUnitPickupRepository,
    ) {
    }

    #[Route(
        path: '/facilities/{name<[a-z]+>}/parcel-pickup',
        name: 'app_pickup_parcel',
        methods: ['GET', 'POST'],
    )]
    #[IsGranted('ROLE_CUSTOMER')]
    public function __invoke(
        Request $request,
        #[CurrentUser] User $user,
        #[MapEntity(expr: 'repository.findOneCommissionedByName(name)')] LockerFacility $facility,
    ): Response {
        $parcelPickup = new ParcelPickup($facility->getCanonicalName());

        $form = $this
            ->createForm(ParcelPickupType::class, $parcelPickup)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $parcel = $this->parcelUnitRepository->getBySerial($parcelPickup->parcelSerial);

            $locker = $this->parcelLockerRepository->getInUseAtFacilityByUnlockCode($facility, $parcelPickup->unlockCode);
            $locker->pickupParcel($parcelPickup->unlockCode);

            $parcelUnitPickup = new ParcelUnitPickup(
                parcel: $parcel,
                locker: $locker,
                customer: $user,
                unlockCode: $parcelPickup->unlockCode,
            );

            $this->parcelUnitPickupRepository->save($parcelUnitPickup);

            return $this->redirectToRoute('app_view_locker_facility', [
                'name' => $facility->getCanonicalName(),
            ]);
        }

        return $this->render('locker_facility/pickup_parcel.html.twig', [
            'facility' => $facility,
            'form' => $form->createView(),
        ]);
    }
}
