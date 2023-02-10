<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LegalNoticeController extends AbstractController
{
    #[Route('/legal/notice', name: 'app_legal_notice')]
    public function index(Request $request): Response
    {
        if ('fr' == $request->getLocale()) {
            return $this->render('legal_notice/fr_legal_notice.html.twig');
        } else {
        }

        return $this->render('legal_notice/en_legal_notice.html.twig');
    }
}
