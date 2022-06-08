<?php

namespace App\Controller;

use App\Entity\EmergencyQualificationQuestion;
use App\Entity\School;
use App\Entity\SchoolEmergencyQualificationQuestion;
use App\Entity\Student;
use App\Form\EmergencyQualificationType;
use App\Form\HousingGenericRequestType;
use App\Model\HousingGenericRequestModel;
use App\Service\EmailService;
use App\Service\EmergencyQuestionsSessionService;
use Doctrine\ORM\EntityManagerInterface;
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
    public function genericRequest(Request $request, EmailService $emailService, TranslatorInterface $translator): Response
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
                $this->addFlash('success', $translator->trans('housing_request.common.send_success'));
            } catch (TransportExceptionInterface) {
                $this->addFlash('error', $translator->trans('housing_request.common.send_error'));

                return $this->redirectToRoute('app_housing_generic_request');
            }

            return $this->redirectToRoute('app_home');
        }

        return $this->render('housing_request/generic.html.twig', [
            'requestForm' => $form->createView(),
            'destinationSchool' => $destinationSchool,
        ]);
    }

    #[Route('/emergency-qualification', name: 'app_housing_emergency_request_qualification')]
    public function emergencyRequestQualification(
        Request $request,
        EntityManagerInterface $entityManager,
        EmergencyQuestionsSessionService $emergencyQuestionsSession
    ): Response {
        $questions = $entityManager->getRepository(EmergencyQualificationQuestion::class)->findAll();
        $form = $this->createForm(EmergencyQualificationType::class, [], ['qualification_questions' => $questions]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $selectedQuestions = [];
            /** @var bool $value */
            foreach ($form->getData() as $key => $value) {
                if ($value) {
                    $questionId = explode('_', $key)[1];
                    $question = current(array_filter($questions, fn ($val) => $val->getId() == $questionId));
                    if ($question) {
                        $selectedQuestions[] = $question;
                    }
                }
            }
            $emergencyQuestionsSession->storeQuestions($selectedQuestions);

            return $this->redirectToRoute('app_housing_emergency_request_send');
        }

        return $this->render('housing_request/emergency_qualification.html.twig', [
            'qualificationForm' => $form->createView(),
        ]);
    }

    #[Route('/emergency-send', name: 'app_housing_emergency_request_send')]
    public function emergencyRequestSend(
        Request $request,
        EmailService $emailService,
        EmergencyQuestionsSessionService $emergencyQuestionsSession,
        EntityManagerInterface $entityManager,
        TranslatorInterface $translator,
    ): Response {
        $qualificationQuestions = $emergencyQuestionsSession->getStoredQuestions();
        if (null === $qualificationQuestions) {
            return $this->redirectToRoute('app_housing_emergency_request_qualification');
        }

        /** @var Student $student */
        $student = $this->getUser();
        $destinationSchool = $student->getSchool();
        $additionalDestinationEmails = $this->getAdditionalEmailsFromQualificationQuestions(
            $entityManager, $qualificationQuestions, $destinationSchool
        );

        $form = $this->createForm(HousingGenericRequestType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $emailService->sendHousingEmergencyEmail(
                    new HousingGenericRequestModel($form->get('body')->getData(), $student),
                    $destinationSchool,
                    $qualificationQuestions,
                    $additionalDestinationEmails
                );
                $this->addFlash('success', $translator->trans('housing_request.common.send_success'));
            } catch (TransportExceptionInterface) {
                $this->addFlash('error', $translator->trans('housing_request.common.send_error'));

                return $this->redirectToRoute('app_housing_emergency_request_send');
            }
            $emergencyQuestionsSession->clearStoredQuestions();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('housing_request/emergency.html.twig', [
            'requestForm' => $form->createView(),
            'destinationSchool' => $destinationSchool,
            'additionalDestinationEmails' => $additionalDestinationEmails,
        ]);
    }

    private function getAdditionalEmailsFromQualificationQuestions(
        EntityManagerInterface $entityManager, array $qualificationQuestions, ?School $school
    ): array {
        $additionalDestinationEmails = [];
        if ($school and sizeof($qualificationQuestions) > 0) {
            $schoolQualificationQuestions = $entityManager
                ->getRepository(SchoolEmergencyQualificationQuestion::class)
                ->getSchoolEmergencyQualificationQuestions($school, $qualificationQuestions)
                ->getQuery()
                ->getResult();
            /** @var SchoolEmergencyQualificationQuestion $schoolQuestion */
            foreach ($schoolQualificationQuestions as $schoolQuestion) {
                $additionalDestinationEmails = array_merge(
                    $additionalDestinationEmails, $schoolQuestion->getContactList()
                );
            }
        }

        return $additionalDestinationEmails;
    }
}
