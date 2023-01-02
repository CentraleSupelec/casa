<?php

namespace App\Controller;

use App\Entity\School;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FaqController extends AbstractController
{
    #[Route('/faq', name: 'app_faq')]
    public function index(EntityManagerInterface $em): Response
    {
        $schools = $em->getRepository(School::class)->findAll();

        return $this->render('faq/index.html.twig', [
            'schools' => $schools,
        ]);
    }
}
