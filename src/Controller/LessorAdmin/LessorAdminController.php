<?php

namespace App\Controller\LessorAdmin;

use App\Entity\Housing;
use App\Entity\HousingGroup;
use App\Form\Lessor\HousingFormType;
use App\Form\Lessor\HousingGroupFormType;
use App\Form\SearchHousingGroupType;
use App\Model\SearchHousingGroupCriteriaModel;
use App\Repository\HousingGroupRepository;
use App\Repository\HousingRepository;
use App\Service\ImageUrlService;
use App\Service\MapService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/lessor/admin')]
class LessorAdminController extends AbstractController
{
    /**
     *  Housing Group Admin.
     */
    #[Route('/', name: 'app_lessor_admin_housing_group_list')]
    public function housingGroupList(
        EntityManagerInterface $entityManager,
        PaginatorInterface $paginator,
        ImageUrlService $imageUrlService,
        Request $request,
        ): Response {
        /** @var LessorUserAdmin */
        $user = $this->getUser();
        $lessor = $user->getLessor();

        $searchHousingGroupCriteria = new SearchHousingGroupCriteriaModel();
        $cities = $entityManager->getRepository(HousingGroup::class)->getLessorDistinctCities($lessor);

        $form = $this->createForm(SearchHousingGroupType::class, $searchHousingGroupCriteria);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $searchHousingGroupCriteria = $form->getData();
            $searchHousingGroupCriteria->setCity(strtoupper($searchHousingGroupCriteria->getCity()));
        }

        $housingGroupQueryBuilder = $entityManager
            ->getRepository(HousingGroup::class)
            ->getLessorHousingGroupListQueryBuilder($lessor, $searchHousingGroupCriteria);

        $pagination = $paginator->paginate(
            $housingGroupQueryBuilder,
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('lessor_admin/housing_group_list.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView(),
            'cities' => $cities,
            'imageBaseUrl' => $imageUrlService->getImageBaseUrl(),
        ]);
    }

    #[Route('/housing-group/create', name: 'app_lessor_admin_housing_group_create')]
    public function createHousingGroup(Request $request,
        EntityManagerInterface $em,
        MapService $mapService): Response
    {
        /** @var LessorUserAdmin */
        $user = $this->getUser();
        $lessor = $user->getLessor();

        $housingGroup = new HousingGroup();
        $housingGroup->setLessor($lessor);

        $form = $this->createForm(HousingGroupFormType::class, $housingGroup, [
            'form_part' => HousingGroupFormType::PRINCIPAL,
        ]);

        $form->handleRequest($request);

        try {
            if ($form->isSubmitted() && $form->isValid()) {
                /** @var HousingGroup */
                $updatedHousingGroup = $form->getData();
                $coordinates = $mapService->getCoordinatesFromAddress($updatedHousingGroup->getAddress());
                $updatedHousingGroup->getAddress()->setCoordinates($coordinates);
                $em->persist($updatedHousingGroup);
                $em->flush();
                $this->addFlash('success', 'Groupe de logements créé');

                return $this->redirectToRoute('app_lessor_admin_housing_group_edit', [
                    'id' => $updatedHousingGroup->getId(),
                    'form_part' => HousingGroupFormType::PRINCIPAL,
                ]);
            }
        } catch (\Exception $ex) {
            $this->addFlash('error', 'Impossible d\'enregistrer le groupe de logement');
        }

        return $this->render('lessor_admin/housing_group_edit.html.twig', [
            'form' => $form->createView(),
            'form_mode' => HousingGroupFormType::MODE_CREATE,
        ]);
    }

    #[Route('/housing-group/edit/{id}/{form_part}', name: 'app_lessor_admin_housing_group_edit')]
    public function editHousingGroup(string $id,
        Request $request,
        HousingGroupRepository $hr,
        EntityManagerInterface $em,
        string $form_part): Response
    {
        /** @var LessorUserAdmin */
        $user = $this->getUser();
        $lessor = $user->getLessor();

        $housingGroup = $hr->find($id);

        // Security Check that housingGroupLessor matches connected.
        if ($lessor->getId() != $housingGroup->getLessor()->getId()) {
            return $this->redirectToRoute('app_lessor_admin_housing_group_list');
        }

        $form = $this->createForm(HousingGroupFormType::class, $housingGroup, [
            'form_part' => $form_part,
        ]);

        $form->handleRequest($request);

        try {
            if ($form->isSubmitted() && $form->isValid()) {
                $em->flush();
                $this->addFlash('success', 'Groupe de Logements mis à jour !');
            }
        } catch (\Exception $ex) {
            $this->addFlash('error', 'La mise à jour du groupe de logement a échouée !');
        }

        return $this->render('lessor_admin/housing_group_edit.html.twig', [
            'form' => $form->createView(),
            'form_mode' => HousingGroupFormType::MODE_EDIT,
        ]);
    }

    #[Route('/housing/create/{idHousingGroup}', name: 'app_lessor_admin_housing_create')]
    public function createHousing(
        string $idHousingGroup,
        Request $request,
        EntityManagerInterface $em): Response
    {
        /** @var LessorUserAdmin */
        $user = $this->getUser();
        $lessor = $user->getLessor();

        $housingGroup = $em->getRepository(HousingGroup::class)->find($idHousingGroup);

        if ($lessor->getId() != $housingGroup->getLessor()->getId()) {
            return $this->redirectToRoute('app_lessor_admin_housing_group_list');
        }

        $housing = new Housing();
        $housing->setHousingGroup($housingGroup);

        $form = $this->createForm(HousingFormType::class, $housing, [
            'form_part' => HousingFormType::PRINCIPAL,
        ]);

        try {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var Housing */
                $updatedHousing = $form->getData();
                $em->persist($updatedHousing);
                $em->flush();
                $this->addFlash('success', 'Logement créé !');

                return $this->redirectToRoute('app_lessor_admin_housing_edit', [
                    'id' => $updatedHousing->getId(),
                    'form_part' => HousingFormType::PRINCIPAL,
                    'form_mode' => HousingFormType::MODE_EDIT,
                ]);
            }
        } catch (\Exception $ex) {
            $this->addFlash('error', 'Erreur lors de la creation du logement!');
        }

        return $this->render('lessor_admin/housing_edit.html.twig', [
            'form' => $form->createView(),
            'form_mode' => HousingFormType::MODE_CREATE,
        ]);
    }

    #[Route('/housing/edit/{id}/{form_part}', name: 'app_lessor_admin_housing_edit')]
    public function editHousing(string $id,
    Request $request,
    HousingRepository $hr,
    EntityManagerInterface $em,
    ImageUrlService $imageUrlService,
    string $form_part): Response
    {
        /** @var LessorUserAdmin */
        $user = $this->getUser();
        $lessor = $user->getLessor();

        $housingElement = $hr->find($id);

        if ($lessor->getId() != $housingElement->getHousingGroup()->getLessor()->getId()) {
            return $this->redirectToRoute('app_lessor_admin_housing_group_list');
        }

        $form = $this->createForm(HousingFormType::class, $housingElement, [
            'form_part' => $form_part,
        ]);
        try {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($housingElement);
                $em->flush();

                // picture numbering is not ok if we do not reload.
                $housingElement = $hr->find($id);

                $this->addFlash('success', 'Logement mis à jour !');
            } elseif ($form->isSubmitted() && !$form->isValid()) {
                $this->addFlash('error', 'Veuillez vérifier votre saisie');
            }
        } catch (\Exception $ex) {
            $this->addFlash('error', 'Veuillez vérifier votre saisie ');
        }

        return $this->render('lessor_admin/housing_edit.html.twig', [
            'form' => $form->createView(),
            'imageBaseUrl' => $imageUrlService->getImageBaseUrl(),
            'form_mode' => HousingFormType::MODE_EDIT,
        ]);
    }
}
