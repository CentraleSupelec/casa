<?php

namespace App\Controller\LessorAdmin;

use App\Repository\LessorAdminUserRepository;
use App\Security\EmailVerifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

#[Route('/lessor/admin')]
class AuthenticationController extends AbstractController
{
    public function __construct(
        private readonly EmailVerifier $emailVerifier, private readonly TranslatorInterface $translator
    ) {
    }

    #[Route('/login', name: 'lessor_admin_login')]
    public function index(AuthenticationUtils $authenticationUtils, LessorAdminUserRepository $lessorAdminUserRepository): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        $template_data = [
            'error' => $error,
            'lastUsername' => $lastUsername,
        ];

        $user = $lessorAdminUserRepository->findOneBy(['email' => $lastUsername]);

        $template_data['verificationToken'] = $user?->getVerificationToken();

        return $this->render('authentication/lessor/login.html.twig', $template_data);
    }

    #[Route('/login-check', name: 'lessor_admin_login_check')]
    public function authenticate()
    {
    }

    #[Route('/logout', name: 'lessor_admin_logout')]
    public function logout()
    {
    }

    #[Route('/verify/email', name: 'lessor_admin_verify_email')]
    public function verifyUserEmail(
        Request $request,
        LessorAdminUserRepository $lessorAdminUserRepository,
        TranslatorInterface $translator): Response
    {
        $verificationToken = $request->get('verificationToken');

        if (null === $verificationToken) {
            return $this->redirectToRoute('lessor_admin_login');
        }

        $user = $lessorAdminUserRepository->findOneBy(['verificationToken' => $verificationToken]);

        if (null === $user) {
            return $this->redirectToRoute('lessor_admin_login');
        }

        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('lessor_admin_login');
        }

        $this->addFlash('success', $this->translator->trans('authentication.email.confirmed'));

        return $this->redirectToRoute('app_login');
    }
}
