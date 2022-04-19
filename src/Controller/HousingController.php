<?php

namespace App\Controller;

use App\Entity\Housing;
use App\Service\ImageUrlService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HousingController extends AbstractController
{
    #[Route('/housing/{id}', name: 'app_housing_detail')]
    public function detail(int $id, EntityManagerInterface $entityManager, ImageUrlService $imageUrl): Response
    {
        $housing = $entityManager->getRepository(Housing::class)->findOneBy(['id' => $id]);

        return $this->render('housing/index.html.twig', [
            'housing' => $housing,
            'imageBaseUrl' => $imageUrl->getImageBaseUrl(),
        ]);
    }
}
