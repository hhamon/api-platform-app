<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\LockerFacility;
use App\Entity\User;
use App\ParcelHandling\DepositParcelUnit;
use App\ParcelHandling\Exception\NoSuitableLockerForParcelException;
use App\ParcelHandling\Exception\ParcelUnitNotFoundException;
use App\ParcelHandling\Form\ParcelDepositType;
use App\ParcelHandling\Model\ParcelDeposit;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class DepositParcelController extends AbstractController
{
    public function __construct(
        private readonly DepositParcelUnit $depositParcelUnit,
    ) {
    }

    #[Route(
        path: '/facilities/{name<[a-z]+>}/parcel-deposit',
        name: 'app_deposit_parcel',
        methods: ['GET', 'POST'],
    )]
    #[IsGranted('ROLE_DELIVERY_MAN')]
    public function __invoke(
        Request $request,
        #[CurrentUser] User $user,
        #[MapEntity(expr: 'repository.findOneCommissionedByName(name)')] LockerFacility $facility,
    ): Response {
        $parcelDeposit = new ParcelDeposit();

        $form = $this
            ->createForm(ParcelDepositType::class, $parcelDeposit)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->depositParcelUnit->deposit($facility, $parcelDeposit->parcelSerial, $user);
            } catch (ParcelUnitNotFoundException|NoSuitableLockerForParcelException $e) {
                $form->addError(new FormError($e->getMessage()));

                return $this->render('locker_facility/deposit_parcel.html.twig', [
                    'facility' => $facility,
                    'form' => $form->createView(),
                ]);
            }

            return $this->redirectToRoute('app_view_locker_facility', [
                'name' => $facility->getCanonicalName(),
            ]);
        }

        return $this->render('locker_facility/deposit_parcel.html.twig', [
            'facility' => $facility,
            'form' => $form->createView(),
        ]);
    }
}
