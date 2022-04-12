<?php

namespace App\Controller;

use App\Entity\Housing;
use App\Service\ImageUrlService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager, ImageUrlService $imageUrl): Response
    {
        $housings = $entityManager->getRepository(Housing::class)->findAll();

        return $this->render('home/index.html.twig', [
            'housings' => $housings,
            'imageBaseUrl' => $imageUrl->getImageBaseUrl(),
        ]);
    }
}
