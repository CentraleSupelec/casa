<?php

namespace App\Security;

use App\Constants;
use App\Model\PsuhUserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EmailVerifier
{
    private VerifyEmailHelperInterface $verifyEmailHelper;
    private MailerInterface $mailer;
    private EntityManagerInterface $entityManager;
    private TranslatorInterface $translator;

    public function __construct(VerifyEmailHelperInterface $helper, MailerInterface $mailer, EntityManagerInterface $manager, TranslatorInterface $translator)
    {
        $this->verifyEmailHelper = $helper;
        $this->mailer = $mailer;
        $this->entityManager = $manager;
        $this->translator = $translator;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendEmailConfirmation(string $verifyEmailRouteName, PsuhUserInterface $user): void
    {
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            $verifyEmailRouteName,
            $user->getId(),
            $user->getEmail(),
            ['verificationToken' => $user->getVerificationToken()]
        );

        $email = $this->getVerificationEmail($user);

        $context = $email->getContext();
        $context['signed_url'] = $signatureComponents->getSignedUrl();
        $context['expires_at_message_key'] = $signatureComponents->getExpirationMessageKey();
        $context['expires_at_message_data'] = $signatureComponents->getExpirationMessageData();

        $email->context($context);

        $this->mailer->send($email);
    }

    /**
     * @throws VerifyEmailExceptionInterface
     */
    public function handleEmailConfirmation(Request $request, PsuhUserInterface $user): void
    {
        $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getEmail());

        $user->setVerified(true);
        $user->setEnabled(true);
        $user->setVerificationToken(null);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    private function getVerificationEmail(PsuhUserInterface $user): TemplatedEmail
    {
        return (new TemplatedEmail())
            ->from(new Address(Constants::APP_EMAIL_ADDRESS, $this->translator->trans('general.email_name')))
            ->to($user->getEmail())
            ->subject($this->translator->trans('authentication.verification_email.subject'))
            ->htmlTemplate('emails/confirmation_email.html.twig');
    }
}
