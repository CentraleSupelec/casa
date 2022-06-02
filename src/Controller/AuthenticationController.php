<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\RegistrationFormType;
use App\Repository\StudentRepository;
use App\Security\EmailVerifier;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class AuthenticationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    private TranslatorInterface $translator;

    public function __construct(EmailVerifier $emailVerifier, TranslatorInterface $translator)
    {
        $this->emailVerifier = $emailVerifier;
        $this->translator = $translator;
    }

    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils, StudentRepository $studentRepository): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        $template_data = [
            'error' => $error,
            'last_username' => $lastUsername,
        ];

        $user = $studentRepository->findOneBy(['email' => $lastUsername]);

        $template_data['verification_token'] = $user?->getVerificationToken();

        return $this->render('authentication/login.html.twig', $template_data);
    }

    #[Route('/login_check', name: 'app_login_check')]
    public function authenticate()
    {
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout()
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserService $userService): Response
    {
        $student = new Student();
        $form = $this->createForm(RegistrationFormType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $student->setVerificationToken(Uuid::v4());
            $userService->updateUser($student);

            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $student);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('authentication/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/verify/send', name: 'app_send_verification_email')]
    public function sendVerificationEmail(Request $request, AuthenticationUtils $authenticationUtils, StudentRepository $studentRepository, TranslatorInterface $translator): Response
    {
        $lastUsername = $authenticationUtils->getLastUsername();

        $verificationToken = $request->get('verificationToken');

        if (null === $verificationToken) {
            $this->addFlash('error', $translator->trans('authentication.log_in.send_verification_error'));

            return $this->redirectToRoute('app_register');
        }

        $user = $studentRepository->findOneBy(['verificationToken' => $verificationToken]);

        if (null === $user) {
            $this->addFlash('error', $translator->trans('authentication.log_in.send_verification_error'));

            return $this->redirectToRoute('app_register');
        }

        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user);

        return $this->render('authentication/login.html.twig', [
            'error' => null,
            'last_username' => $lastUsername,
            'verification_token' => null,
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, StudentRepository $studentRepository, TranslatorInterface $translator): Response
    {
        $verificationToken = $request->get('verificationToken');

        if (null === $verificationToken) {
            return $this->redirectToRoute('app_register');
        }

        $user = $studentRepository->findOneBy(['verificationToken' => $verificationToken]);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        $this->addFlash('success', $this->translator->trans('authentication.email.confirmed'));

        return $this->redirectToRoute('app_login');
    }
}
