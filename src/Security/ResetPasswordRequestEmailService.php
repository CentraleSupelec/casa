<?php

namespace App\Security;

use App\Constants;
use App\Entity\LessorAdminUser;
use App\Model\PsuhUserInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordToken;

class ResetPasswordRequestEmailService
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly MailerInterface $mailer
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendResetPasswordEmail(PsuhUserInterface $user, ResetPasswordToken $resetPasswordToken): void
    {
        $this->mailer->send($this->getResetPasswordEmail($user, $resetPasswordToken));
    }

    private function getResetPasswordEmail(PsuhUserInterface $user, ResetPasswordToken $resetPasswordToken): TemplatedEmail
    {
        if ($user instanceof LessorAdminUser) {
            $template = 'emails/lessor_reset_password_email.html.twig';
        } else {
            $template = 'emails/reset_password_email.html.twig';
        }

        return (new TemplatedEmail())
            ->from(new Address(Constants::APP_EMAIL_ADDRESS, $this->translator->trans('general.email_name')))
            ->to($user->getEmail())
            ->subject($this->translator->trans('authentication.reset_password.email.subject'))
            ->htmlTemplate($template)
            ->context([
                'resetToken' => $resetPasswordToken,
            ]);
    }
}
