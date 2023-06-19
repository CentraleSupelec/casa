<?php

namespace App\Service;

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
        private readonly MailerInterface $mailer,
        private readonly string $appEmailAddress,
        private readonly string $housingRequestDefaultEmail,
        private readonly string $housingRequestArchiveEmail,
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
        $destinationEmail = $destinationSchool?->getHousingServiceEmail() ?: $this->housingRequestDefaultEmail;

        $contentEmail = new TemplatedEmail();

        $contentEmail->from(new Address($this->appEmailAddress, $this->translator->trans('general.email_name')))
            ->subject($this->translator->trans('housing_request.generic.email.subject'))
            ->to($destinationEmail)
            ->addCc($studentEmail);

        if ($this->housingRequestArchiveEmail != $destinationEmail) {
            $contentEmail->addBcc($this->housingRequestArchiveEmail);
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
        School $destinationSchool = null,
        array $emergencyQualificationQuestions = [],
        array $additionalDestinationEmails = [],
    ): void {
        $studentEmail = $requestModel->getStudent()->getEmail();
        $destinationEmails = array_unique(
            array_merge(
                [$destinationSchool?->getHousingServiceEmail() ?: $this->housingRequestDefaultEmail],
                $additionalDestinationEmails
            )
        );
        $email = (new TemplatedEmail())
            ->from(new Address($this->appEmailAddress, $this->translator->trans('general.email_name')))
            ->subject($this->translator->trans('housing_request.emergency.email.subject'));

        foreach ($destinationEmails as $address) {
            $email->addTo($address);
        }
        $email
            ->addCc($studentEmail);
        // Add BCC to central email if not already the "to"
        if (!in_array($this->housingRequestArchiveEmail, $destinationEmails)) {
            $email->addBcc($this->housingRequestArchiveEmail);
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
