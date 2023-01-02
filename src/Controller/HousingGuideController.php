<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/housing/guide')]
class HousingGuideController extends AbstractController
{
    #[Route('/search', name: 'housing_guide_search')]
    public function search(Request $request): Response
    {
        return $this->render('housing_guide/'.$request->getLocale().'_search.html.twig');
    }

    #[Route('/search/sumup', name: 'housing_guide_search_sumup')]
    public function search_sumup(Request $request): Response
    {
        return $this->render('housing_guide/'.$request->getLocale().'_search_sumup.html.twig');
    }

    #[Route('/housing_cost', name: 'housing_guide_cost')]
    public function cost(Request $request): Response
    {
        return $this->render('housing_guide/'.$request->getLocale().'_cost.html.twig');
    }

    #[Route('/housing_cost/sumup', name: 'housing_guide_cost_sumup')]
    public function cost_sumup(Request $request): Response
    {
        return $this->render('housing_guide/'.$request->getLocale().'_cost_sumup.html.twig');
    }

    #[Route('/apply', name: 'housing_guide_apply')]
    public function apply(Request $request): Response
    {
        return $this->render('housing_guide/'.$request->getLocale().'_apply.html.twig');
    }

    #[Route('/apply/sumup', name: 'housing_guide_apply_sumup')]
    public function apply_sumup(Request $request): Response
    {
        return $this->render('housing_guide/'.$request->getLocale().'_apply_sumup.html.twig');
    }
}
