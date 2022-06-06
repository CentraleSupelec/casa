<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\HousingGenericRequestType;
use App\Model\HousingGenericRequestModel;
use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/housing-request')]
class HousingRequestController extends AbstractController
{
    #[Route('', name: 'app_housing_generic_request')]
    public function index(Request $request, EmailService $emailService, TranslatorInterface $translator): Response
    {
        /** @var Student $student */
        $student = $this->getUser();
        $destinationSchool = $student->getSchool();
        $form = $this->createForm(HousingGenericRequestType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $emailService->sendHousingGenericRequestEmail(
                    new HousingGenericRequestModel($form->get('body')->getData(), $student),
                    $destinationSchool
                );
                $this->addFlash('success', $translator->trans('housing_request.send_success'));
            } catch (TransportExceptionInterface) {
                $this->addFlash('error', $translator->trans('housing_request.send_error'));

                return $this->redirectToRoute('app_housing_generic_request');
            }

            return $this->redirectToRoute('app_home');
        }

        return $this->render('housing_request/index.html.twig', [
            'requestForm' => $form->createView(),
            'destinationSchool' => $destinationSchool,
        ]);
    }
}
