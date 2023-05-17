<?php

namespace App\Controller;

use App\Entity\Housing;
use App\Entity\HousingGroup;
use App\Entity\School;
use App\Entity\Student;
use App\Form\SearchHousingType;
use App\Model\SearchCriteriaModel;
use App\Model\StudentProfileCriteriaModel;
use App\Service\ImageUrlService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class HousingController extends AbstractController
{
    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    #[Route('/housing/detail/{id}', name: 'app_housing_detail')]
    public function detail(string $id, EntityManagerInterface $entityManager, ImageUrlService $imageUrl): Response
    {
        /** @var Student */
        $student = $this->getUser();
        $studentProfileCriteria = new StudentProfileCriteriaModel($student instanceof Student ? $student : null);

        /**
         * $housingElement is an object with the following structure:
         * { 0: Housing, isPriority: bool, hasCriteria: bool, hasSchoolCriteria: bool, hasSocialScholarshipCriteria: bool }.
         */
        $housingElement = $entityManager
            ->getRepository(Housing::class)
            ->getHousingByIdQueryBuilder($studentProfileCriteria, $id)
            ->getQuery()
            ->getSingleResult();

        $schools = $entityManager
            ->getRepository(School::class)
            ->getHousingSchoolsWithCriteria($housingElement[0])
            ->getQuery()
            ->getResult();

        return $this->render('housing/index.html.twig', [
            'housing' => $housingElement[0],
            'isPriority' => $housingElement['isPriority'],
            'hasCriteria' => $housingElement['hasCriteria'],
            'hasSchoolCriteria' => $housingElement['hasSchoolCriteria'],
            'hasSocialScholarshipCriteria' => $housingElement['hasSocialScholarshipCriteria'],
            'schools' => $schools,
            'imageBaseUrl' => $imageUrl->getImageBaseUrl(),
        ]);
    }

    #[Route('/housing/list', name: 'app_housing_list')]
    public function list(EntityManagerInterface $entityManager,
        ImageUrlService $imageUrl,
        PaginatorInterface $paginator,
        TranslatorInterface $translator,
        Request $request): Response
    {
        $student = $this->getUser();
        $searchHousingCriteria = $request->getSession()->get('search_criteria');

        if (null == $searchHousingCriteria) {
            $searchHousingCriteria = new SearchCriteriaModel();
        }

        $cities = $entityManager->getRepository(HousingGroup::class)->getDistinctCities();

        $studentProfileCriteria = new StudentProfileCriteriaModel($student instanceof Student ? $student : null);

        $form = $this->createForm(SearchHousingType::class, $searchHousingCriteria,
            [
                'method' => 'GET',
                'advancedSearch' => true,
                'locale' => $request->getLocale(),
            ],
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $searchHousingCriteria = $form->getData();
            $searchHousingCriteria->setCity(strtoupper($searchHousingCriteria->getCity()));

            $request->getSession()->set('search_criteria', $searchHousingCriteria);
        }

        $housingsListQueryBuilder = $entityManager
            ->getRepository(Housing::class)
            ->getHousingListQueryBuilder($searchHousingCriteria, $studentProfileCriteria);

        /**
         * $pagination is an array of objects with the following structure:
         * { 0: Housing, isPriority: bool, hasCriteria: bool }.
         */
        $pagination = $paginator->paginate(
            $housingsListQueryBuilder,
            $request->query->getInt('page', 1),
            $searchHousingCriteria->getMaxResultsByPage(),
        );

        // Add some information to title for accessibility purpose

        $title_informations = $translator->trans(
            'housing.list.title_information',
            [
                '%searchcriteria%' => $this->getTitleInformation($searchHousingCriteria),
                '%current%' => $pagination->getCurrentPageNumber(),
                '%totalitem%' => $pagination->getTotalItemCount(), ], null, $request->getLocale(),
        );

        return $this->render('housing/list.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination,
            'imageBaseUrl' => $imageUrl->getImageBaseUrl(),
            'cities' => $cities,
            'title_information' => $title_informations,
        ]);
    }

    #[Route('/housing/{id}/add-bookmark', name: 'app_housing_add_bookmark')]
    public function addBookmark(string $id, EntityManagerInterface $entityManager, Request $request): Response
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
    public function removeBookmark(string $id, EntityManagerInterface $entityManager, Request $request): Response
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

    private function redirectAfterBookmarkAction(string $housingId, string $origin, ?array $query): Response
    {
        $redirection_params = [];
        if (null !== $query) {
            foreach ($query as $key => $value) {
                $redirection_params[$key] = $value;
            }
        }

        if ('app_housing_detail' === $origin) {
            $redirection_params['id'] = $housingId;

            return $this->redirectToRoute('app_housing_detail', $redirection_params);
        }

        return $this->redirectToRoute($origin, $redirection_params);
    }

    private function getTitleInformation(SearchCriteriaModel $searchHousingCriteria): string
    {
        $searchCriteria = $searchHousingCriteria->getCity();
        if ($searchHousingCriteria->getMaxPrice()) {
            $searchCriteria = $searchCriteria.' '.$searchHousingCriteria->getMaxPrice().' €';
        }
        if ($searchHousingCriteria->getMinArea()) {
            $searchCriteria = $searchCriteria.' '.$searchHousingCriteria->getMinArea().' m2';
        }

        return $searchCriteria ?? '';
    }
}
