<?php

namespace App\Controller;

use App\Entity\Housing;
use App\Form\SearchHousingType;
use App\Model\SearchCriteriaModel;
use App\Service\ImageUrlService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HousingController extends AbstractController
{
    #[Route('/housing/{id}', name: 'app_housing_detail', requirements: ['id' => '\d+'])]
    public function detail(int $id, EntityManagerInterface $entityManager, ImageUrlService $imageUrl): Response
    {
        $housing = $entityManager->getRepository(Housing::class)->findOneBy(['id' => $id]);

        return $this->render('housing/index.html.twig', [
            'housing' => $housing,
            'imageBaseUrl' => $imageUrl->getImageBaseUrl(),
        ]);
    }

    #[Route('/housing/list', name: 'app_housing_list')]
    public function list(EntityManagerInterface $entityManager,
        ImageUrlService $imageUrl,
        PaginatorInterface $paginator,
        Request $request): Response
    {
        $pagination = null;

        $searchHousingCriteria = new SearchCriteriaModel();
        $form = $this->createForm(SearchHousingType::class, $searchHousingCriteria);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $searchHousingCriteria = $form->getData();

            $housingsQuery = $entityManager->getRepository(Housing::class)
                ->qbFindByCriteria($searchHousingCriteria);

            $pagination = $paginator->paginate(
                $housingsQuery,
                $request->query->getInt('page', 1),
                $searchHousingCriteria->getMaxResultsByPage(),
            );
        }

        return $this->render('housing/list.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination,
            'imageBaseUrl' => $imageUrl->getImageBaseUrl(),
        ]);
    }
}
