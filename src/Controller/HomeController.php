<?php

namespace App\Controller;

use App\Form\SearchHousingType;
use App\Model\SearchCriteriaModel;
use App\Repository\HousingGroupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, HousingGroupRepository $housingGroupRepository): Response
    {
        $searchHousing = $request->getSession()->get('search_criteria');

        if (null == $searchHousing) {
            $searchHousing = new SearchCriteriaModel();
        }

        $cities = $housingGroupRepository->getDistinctCities();

        $form = $this->createForm(SearchHousingType::class, $searchHousing,
            [
                'action' => $this->generateUrl('app_housing_list'),
                'method' => 'GET',
                'locale' => $request->getLocale(),
            ]);

        return $this->renderForm('home/index.html.twig', [
            'form' => $form,
            'cities' => $cities,
        ]);
    }
}
