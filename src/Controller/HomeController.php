<?php

namespace App\Controller;

use App\Form\SearchHousingType;
use App\Model\SearchCriteriaModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $searchHousing = new SearchCriteriaModel();
        $form = $this->createForm(SearchHousingType::class, $searchHousing,
            [
                'action' => $this->generateUrl('app_housing_list'),
                'method' => 'GET',
            ]);

        return $this->renderForm('home/index.html.twig', [
            'form' => $form,
        ]);
    }
}
