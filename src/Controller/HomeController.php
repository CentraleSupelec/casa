<?php

namespace App\Controller;

use App\Entity\Housing;
use App\Service\ImageUrlService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    // TODO: à remplacer par un choix utilisateur dans l'interface
    private const MAX_RESULT_PAGE = 2;

    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager,
        ImageUrlService $imageUrl,
        PaginatorInterface $paginator,
        Request $request): Response
    {
        $housingsQuery = $entityManager->getRepository(Housing::class)->qbFindAll();

        $pagination = $paginator->paginate(
            $housingsQuery,
            $request->query->getInt('page', 1),
            HomeController::MAX_RESULT_PAGE
        );

        return $this->render('home/index.html.twig', [
            'pagination' => $pagination,
            'imageBaseUrl' => $imageUrl->getImageBaseUrl(),
        ]);
    }
}
