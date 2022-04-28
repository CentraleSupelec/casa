<?php

namespace App\Controller;

use App\Entity\Student;
use App\Service\ImageUrlService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{
    #[Route('/student', name: 'app_student')]
    public function index(ImageUrlService $imageUrlService): Response
    {
        $student = $this->getUser();

        if (!$student instanceof Student) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('student/index.html.twig', [
            'student' => $student,
            'imageBaseUrl' => $imageUrlService->getImageBaseUrl(),
        ]);
    }
}
