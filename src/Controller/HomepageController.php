<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\LockerFacilityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class HomepageController extends AbstractController
{
    public function __construct(
        private readonly LockerFacilityRepository $facilityRepository,
    ) {
    }

    #[Route(path: '/', name: 'app_homepage', methods: 'GET')]
    public function __invoke(Request $request): Response
    {
        /** @var array{number?: numeric-string|positive-int, size?: numeric-string|positive-int} $page */
        $page = $request->query->all('page');

        $paginator = $this->facilityRepository->paginateForHomepage(
            (int) ($page['number'] ?? 1),
            (int) ($page['size'] ?? 24),
        );

        return $this->render('homepage/index.html.twig', [
            'count' => $paginator->getTotalCount(),
            'facilities' => $paginator,
        ]);
    }
}
