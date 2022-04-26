<?php

namespace App\Controller;

use App\Entity\Student;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{
    #[Route('/student', name: 'app_student')]
    public function index(): Response
    {
        $user = $this->getUser();

        if (!$user instanceof Student) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('student/index.html.twig', [
            'user' => $user,
        ]);
    }
}
