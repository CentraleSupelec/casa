<?php

namespace App\Controller\LessorAdmin;

use App\Entity\Housing;
use App\Entity\HousingGroup;
use App\Form\Lessor\HousingFormType;
use App\Form\Lessor\HousingGroupFormType;
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

        $housingGroupQueryBuilder = $entityManager
            ->getRepository(HousingGroup::class)
            ->getLessorHousingGroupListQueryBuilder($lessor);

        $pagination = $paginator->paginate(
            $housingGroupQueryBuilder,
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('lessor_admin/housing_group_list.html.twig', [
            'pagination' => $pagination,
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

        $form = $this->createForm(HousingGroupFormType::class, $housingGroup, [
            'form_part' => $form_part,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var HousingGroup */
            $updatedHousingGroup = $form->getData();

            $em->persist($updatedHousingGroup);
            $em->flush();
            $this->addFlash('success', 'Groupe de Logements mis à jour !');
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

        $housing = new Housing();

        $housingGroup = $em->getRepository(HousingGroup::class)->find($idHousingGroup);
        $housing->setHousingGroup($housingGroup);

        $form = $this->createForm(HousingFormType::class, $housing, [
            'form_part' => HousingFormType::PRINCIPAL,
        ]);

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
        $housingElement = $hr->find($id);

        /** @var LessorUserAdmin */
        $user = $this->getUser();
        // $lessor = $user->getLessor();

        $form = $this->createForm(HousingFormType::class, $housingElement, [
            'form_part' => $form_part,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Housing */
            $updatedHousing = $form->getData();

            $em->persist($updatedHousing);
            $em->flush();
            $this->addFlash('success', 'Logement mis à jour !');
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Veuillez vérifier votre saisie');
        }

        return $this->render('lessor_admin/housing_edit.html.twig', [
            'form' => $form->createView(),
            'imageBaseUrl' => $imageUrlService->getImageBaseUrl(),
            'form_mode' => HousingFormType::MODE_EDIT,
        ]);
    }
}
