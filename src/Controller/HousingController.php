<?php

namespace App\Controller;

use App\Entity\Housing;
use App\Entity\Student;
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
        $housing = $entityManager->getRepository(Housing::class)->find($id);

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
        $form = $this->createForm(SearchHousingType::class, $searchHousingCriteria,
            ['method' => 'GET']
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $searchHousingCriteria = $form->getData();
        }
        $housingsQuery = $entityManager->getRepository(Housing::class)
            ->qbFindByCriteria($searchHousingCriteria);

        $pagination = $paginator->paginate(
            $housingsQuery,
            $request->query->getInt('page', 1),
            $searchHousingCriteria->getMaxResultsByPage(),
        );

        return $this->render('housing/list.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination,
            'imageBaseUrl' => $imageUrl->getImageBaseUrl(),
        ]);
    }

    #[Route('/housing/{id}/add-bookmark', name: 'app_housing_add_bookmark')]
    public function addBookmark(int $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $origin = $request->get('origin', 'app_housing_list');
        $query = $request->get('query');
        $student = $this->getUser();

        if (!$student instanceof Student) {
            return $this->redirectAfterBookmarkAction($id, $origin, $query);
        }

        $housing = $entityManager->getRepository(Housing::class)->find($id);
        $student->addBookmark($housing);
        $entityManager->flush();

        return $this->redirectAfterBookmarkAction($id, $origin, $query);
    }

    #[Route('/housing/{id}/remove-bookmark', name: 'app_housing_remove_bookmark')]
    public function removeBookmark(int $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $origin = $request->get('origin', 'app_housing_list');
        $query = $request->get('query');

        $student = $this->getUser();

        if (!$student instanceof Student) {
            return $this->redirectAfterBookmarkAction($id, $origin, $query);
        }

        $housing = $entityManager->getRepository(Housing::class)->find($id);
        $student->removeBookmark($housing);
        $entityManager->flush();

        return $this->redirectAfterBookmarkAction($id, $origin, $query);
    }

    private function redirectAfterBookmarkAction(int $housingId, string $origin, ?array $query): Response
    {
        $redirection_params = [];
        if (null !== $query) {
            $redirection_params['search_housing'] = $query['search_housing'];
        }

        if ('app_housing_detail' === $origin) {
            $redirection_params['id'] = $housingId;

            return $this->redirectToRoute('app_housing_detail', $redirection_params);
        }
        if ('app_student' === $origin) {
            return $this->redirectToRoute('app_student');
        }

        return $this->redirectToRoute('app_housing_list', $redirection_params);
    }
}
