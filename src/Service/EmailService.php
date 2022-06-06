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
    public function sendHousingGenericRequestEmail(
        HousingGenericRequestModel $requestModel, ?School $destinationSchool
    ): void {
        $studentEmail = $requestModel->getStudent()->getEmail();
        $destinationEmail = $destinationSchool?->getHousingServiceEmail() ?: Constants::HOUSING_REQUEST_DEFAULT_EMAIL;
        $this->mailer->send(
            (new TemplatedEmail())
            ->from(new Address(Constants::APP_EMAIL_ADDRESS, $this->translator->trans('general.email_name')))
            ->subject($this->translator->trans('housing_request.email.subject'))
            ->to($destinationEmail)
            ->addCc($studentEmail)
            ->addBcc(Constants::HOUSING_REQUEST_ARCHIVE_EMAIL)
            ->replyTo($studentEmail)
            ->htmlTemplate('emails/housing_generic_request.html.twig')
            ->context(['request' => $requestModel])
        );
    }
}
