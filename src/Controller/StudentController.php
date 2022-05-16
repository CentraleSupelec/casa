<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\StudentFormType;
use App\Service\ImageUrlService;
use App\Service\StudentService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{
    #[Route('/student/profile', name: 'app_student_profile')]
    public function profile(ImageUrlService $imageUrlService): Response
    {
        $student = $this->getUser();

        return $this->render('student/profile.html.twig', [
            'student' => $student,
            'mode' => 'display',
            'imageBaseUrl' => $imageUrlService->getImageBaseUrl(),
        ]);
    }

    #[Route('/student/editprofile', name: 'app_student_edit_profile')]
    public function editprofile(
        EntityManagerInterface $entityManager,
        ImageUrlService $imageUrlService,
        StudentService $studentService,
        Request $request): Response
    {
        $mode = 'edit';

        $student = $this->getUser();

        $form = $this->createForm(StudentFormType::class, $student);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Student */
            $updatedStudent = $form->getData();
            $studentService->updateWith($updatedStudent);
            $mode = 'display';
        }

        return $this->render('student/profile.html.twig', [
            'student' => $student,
            'mode' => $mode,
            'form' => $form->createView(),
            'imageBaseUrl' => $imageUrlService->getImageBaseUrl(),
        ]);
    }

    #[Route('/student/bookmarks', name: 'app_student_bookmarks')]
    public function bookmarks(ImageUrlService $imageUrlService): Response
    {
        $student = $this->getUser();

        return $this->render('student/bookmarks.html.twig', [
            'student' => $student,
            'imageBaseUrl' => $imageUrlService->getImageBaseUrl(),
        ]);
    }
}
