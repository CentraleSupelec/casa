<?php

namespace App\Controller\Temporary;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TemporaryController extends AbstractController
{
    #[Route('/soon', name: 'app_soon')]
    public function soon(): Response
    {
        return $this->renderForm('home/index-soon.html.twig');
    }
}
