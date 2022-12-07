<?php

namespace App\Service;

use App\Constants;
use App\Entity\School;
use App\Model\HousingGenericRequestModel;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;

class EmailService
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly MailerInterface $mailer
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     */

    // TODO - Etrange de ne pas avoir la boucle sur les destinataires des établissements.
    public function sendHousingGenericRequestEmail(
        HousingGenericRequestModel $requestModel, ?School $destinationSchool
    ): void {
        $studentEmail = $requestModel->getStudent()->getEmail();
        $destinationEmail = $destinationSchool?->getHousingServiceEmail() ?: Constants::HOUSING_REQUEST_DEFAULT_EMAIL;

        $contentEmail = new TemplatedEmail();

        $contentEmail->from(new Address(Constants::APP_EMAIL_ADDRESS, $this->translator->trans('general.email_name')))
            ->subject($this->translator->trans('housing_request.generic.email.subject'))
            ->to($destinationEmail)
            ->addCc($studentEmail);

        if (Constants::HOUSING_REQUEST_ARCHIVE_EMAIL != $destinationEmail) {
            $contentEmail->addBcc(Constants::HOUSING_REQUEST_ARCHIVE_EMAIL);
        }

        $contentEmail
                ->replyTo($studentEmail)
                ->htmlTemplate('emails/housing_generic_request.html.twig')
                ->context(['request' => $requestModel]);

        $this->mailer->send(
            $contentEmail
        );
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendHousingEmergencyEmail(
        HousingGenericRequestModel $requestModel,
        ?School $destinationSchool = null,
        array $emergencyQualificationQuestions = [],
        array $additionalDestinationEmails = [],
    ): void {
        $studentEmail = $requestModel->getStudent()->getEmail();
        $destinationEmails = array_unique(
            array_merge(
                [$destinationSchool?->getHousingServiceEmail() ?: Constants::HOUSING_REQUEST_DEFAULT_EMAIL],
                $additionalDestinationEmails
            )
        );
        $email = (new TemplatedEmail())
            ->from(new Address(Constants::APP_EMAIL_ADDRESS, $this->translator->trans('general.email_name')))
            ->subject($this->translator->trans('housing_request.emergency.email.subject'));

        foreach ($destinationEmails as $address) {
            $email->addTo($address);
        }
        $email
            ->addCc($studentEmail);
        // Add BCC to central email if not already the "to"
        if (!in_array(Constants::HOUSING_REQUEST_ARCHIVE_EMAIL, $destinationEmails)) {
            $email->addBcc(Constants::HOUSING_REQUEST_ARCHIVE_EMAIL);
        }

        $email
            ->replyTo($studentEmail)
            ->htmlTemplate('emails/housing_emergency_request.html.twig')
            ->context([
                'request' => $requestModel,
                'emergencyQualificationQuestions' => $emergencyQualificationQuestions,
            ]);

        $this->mailer->send(
            $email
        );
    }
}
