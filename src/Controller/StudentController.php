<?php

namespace App\Controller;

use App\Entity\Housing;
use App\Entity\ParentSchool;
use App\Entity\School;
use App\Entity\Student;
use App\Form\ChangePasswordFormType;
use App\Form\StudentFormType;
use App\Model\StudentProfileCriteriaModel;
use App\Service\ImageUrlService;
use App\Service\StudentService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/student')]
class StudentController extends AbstractController
{
    #[Route('/profile', name: 'app_student_profile')]
    public function profile(ImageUrlService $imageUrlService): Response
    {
        $student = $this->getUser();

        return $this->render('student/profile.html.twig', [
            'student' => $student,
            'mode' => 'display',
            'imageBaseUrl' => $imageUrlService->getImageBaseUrl(),
        ]);
    }

    #[Route('/edit-profile', name: 'app_student_edit_profile')]
    public function editProfile(
        EntityManagerInterface $entityManager,
        ImageUrlService $imageUrlService,
        StudentService $studentService,
        Request $request,
    ): Response {
        $mode = 'edit';

        $student = $this->getUser();

        $form = $this->createForm(StudentFormType::class, $student);
        $parentSchools = $entityManager->getRepository(ParentSchool::class)->findAll();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Student */
            $updatedStudent = $form->getData();
            $studentService->updateWith($updatedStudent);

            return $this->redirectToRoute('app_student_profile');
        }

        return $this->render('student/profile.html.twig', [
            'student' => $student,
            'mode' => $mode,
            'form' => $form->createView(),
            'parentSchools' => $parentSchools,
            'imageBaseUrl' => $imageUrlService->getImageBaseUrl(),
        ]);
    }

    #[Route('/reset-password', name: 'app_student_reset_password')]
    public function resetPassword(Request $request, UserService $userService): Response
    {
        /** @var Student $student */
        $student = $this->getUser();

        $form = $this->createForm(ChangePasswordFormType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userService->updateUser($student);

            return $this->redirectToRoute('app_student_profile');
        }

        return $this->render('authentication/reset_password.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }

    #[Route('/bookmarks', name: 'app_student_bookmarks')]
    public function bookmarks(EntityManagerInterface $entityManager, ImageUrlService $imageUrlService): Response
    {
        /** @var Student */
        $student = $this->getUser();

        $studentProfileCriteria = new StudentProfileCriteriaModel($student);

        /**
         * $bookmarksList is an array of objects with the following structure:
         * { 0: Housing, isPriority: bool, hasCriteria: bool }.
         */
        $bookmarksList = $entityManager
            ->getRepository(Housing::class)
            ->getHousingBookmarksQueryBuilder($studentProfileCriteria, $student->getId())
            ->getQuery()
            ->getResult();

        return $this->render('student/bookmarks.html.twig', [
            'student' => $student,
            'bookmarksList' => $bookmarksList,
            'imageBaseUrl' => $imageUrlService->getImageBaseUrl(),
        ]);
    }

    #[Route('/profile/_schools', name: 'app_student_profile_schools')]
    public function schools(EntityManagerInterface $entityManager, Request $request): Response
    {
        $parentId = $request->query->get('parentId');
        $currentSchoolId = $request->query->get('schoolId');

        $parent = $entityManager->getRepository(ParentSchool::class)->find($parentId);
        $schools = $entityManager->getRepository(School::class)->findBy(
            ['parentSchool' => $parent],
            ['parentSchool' => 'ASC', 'campus' => 'ASC', 'name' => 'ASC']);

        return $this->render('student/_profile.select_school_widget.html.twig',
            [
                'schools' => $schools,
                'currentSchoolId' => $currentSchoolId,
            ]);
    }
}
