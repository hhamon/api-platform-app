<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\LockerFacility;
use App\Repository\ParcelLockerRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ViewLockerFacilityController extends AbstractController
{
    public function __construct(
        private readonly ParcelLockerRepository $parcelLockerRepository,
    ) {
    }

    #[Route(path: '/facilities/{name<[a-z]+>}', name: 'app_view_locker_facility', methods: 'GET', )]
    public function __invoke(
        #[MapEntity(expr: 'repository.findOneCommissionedByName(name)')] LockerFacility $facility,
    ): Response {
        return $this->render('locker_facility/view.html.twig', [
            'facility' => $facility,
            'lockers' => $this->parcelLockerRepository->findByFacility($facility),
        ]);
    }
}
