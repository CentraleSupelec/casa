<?php

namespace App\Controller\LessorAdmin;

use App\Entity\LessorAdminUser;
use App\Form\ChangePasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Repository\LessorAdminUserRepository;
use App\Repository\LessorResetPasswordRequestRepository;
use App\Security\ResetPasswordRequestEmailService;
use App\Service\TokenGeneratorService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ExpiredResetPasswordTokenException;
use SymfonyCasts\Bundle\ResetPassword\Exception\InvalidResetPasswordTokenException;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

#[Route('/lessor/admin/reset-password')]
class ResetPasswordController extends AbstractController
{
    use ResetPasswordControllerTrait;

    private const SELECTOR_LENGTH = 20;

    public function __construct(
        private readonly LessorAdminUserRepository $lessorAdminUserRepository,
        private readonly ResetPasswordRequestEmailService $resetPasswordRequestEmailService,
        private readonly ResetPasswordHelperInterface $resetPasswordHelper,
        private readonly LessorResetPasswordRequestRepository $repository,
        private readonly TokenGeneratorService $tokenGenerator
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('', name: 'lessor_reset_password_request')]
    public function request(Request $request, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                return $this->processSendingPasswordResetEmail($form->get('email')->getData(), $translator);
            } catch (\Exception $e) {
                $this->addFlash('error', $translator->trans('authentication.reset_password.request.send_error'));
            }
        }

        return $this->render('authentication/lessor/reset_password_request.html.twig', [
            'requestForm' => $form->createView(),
        ]);
    }

    /**
     * Confirmation page after a user has requested a password reset.
     */
    #[Route('/check-email', name: 'lessor_check_email')]
    public function checkEmail(): Response
    {
        // Generate a fake token if the user does not exist or someone hit this page directly.
        // This prevents exposing whether or not a user was found with the given email address or not
        if (null === ($resetToken = $this->getTokenObjectFromSession())) {
            $resetToken = $this->resetPasswordHelper->generateFakeResetToken();
        }

        return $this->render('authentication/lessor/reset_password_request_send_confirmation.html.twig', [
            'resetToken' => $resetToken,
        ]);
    }

    #[Route('/reset/{token}', name: 'lessor_reset_password')]
    public function reset(
        Request $request,
        TranslatorInterface $translator,
        UserService $userService,
        string $token = null
    ): Response {
        if ($token) {
            // Store the token in session and remove it from the URL, to avoid the URL being
            // loaded in a browser and potentially leaking the token to 3rd party JavaScript.
            $this->storeTokenInSession($token);

            return $this->redirectToRoute('lessor_reset_password');
        }

        $token = $this->getTokenFromSession();

        if (null === $token) {
            throw $this->createNotFoundException('No reset password token found in the URL or in the session.');
        }

        try {
            /** @var LessorAdminUser $user */
            $user = $this->validateTokenAndFetchUser($token);
        } catch (ResetPasswordExceptionInterface $e) {
            $this->addFlash('reset_password_error', sprintf(
                '%s - %s',
                $translator->trans(ResetPasswordExceptionInterface::MESSAGE_PROBLEM_VALIDATE, [], 'ResetPasswordBundle'),
                $translator->trans($e->getReason(), [], 'ResetPasswordBundle')
            ));

            return $this->redirectToRoute('lessor_reset_password_request');
        }

        $form = $this->createForm(ChangePasswordFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->removeResetRequest($token);
            $userService->updateUser($user);
            $this->cleanSessionAfterReset();

            return $this->redirectToRoute('lessor_admin_login');
        }

        return $this->render('authentication/lessor/reset_password.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     */
    private function processSendingPasswordResetEmail(string $email, TranslatorInterface $translator): RedirectResponse
    {
        $user = $this->lessorAdminUserRepository->findOneBy([
            'email' => $email,
        ]);

        // Do not reveal whether a user account was found or not.
        if (!$user) {
            return $this->redirectToRoute('lessor_check_email');
        }

        try {
            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
        } catch (ResetPasswordExceptionInterface $e) {
            $this->addFlash('reset_password_error', sprintf(
                '%s - %s',
                $translator->trans(ResetPasswordExceptionInterface::MESSAGE_PROBLEM_HANDLE, [], 'ResetPasswordBundle'),
                $translator->trans($e->getReason(), [], 'ResetPasswordBundle')
            ));

            return $this->redirectToRoute('lessor_check_email');
        }

        $this->resetPasswordRequestEmailService->sendResetPasswordEmail($user, $resetToken);
        // Store the token object in session for retrieval in check-email route.
        $this->setTokenObjectInSession($resetToken);

        return $this->redirectToRoute('lessor_check_email');
    }

    public function validateTokenAndFetchUser(string $fullToken): object
    {
        $this->repository->removeExpiredResetPasswordRequests();

        if (40 !== \strlen($fullToken)) {
            throw new InvalidResetPasswordTokenException();
        }

        $resetRequest = $this->findResetPasswordRequest($fullToken);

        if (null === $resetRequest) {
            throw new InvalidResetPasswordTokenException();
        }

        if ($resetRequest->isExpired()) {
            throw new ExpiredResetPasswordTokenException();
        }

        $user = $resetRequest->getUser();

        $hashedVerifierToken = $this->tokenGenerator->createToken(
            $resetRequest->getExpiresAt(),
            $this->repository->getUserIdentifier($user),
            substr($fullToken, self::SELECTOR_LENGTH)
        );

        if (false === hash_equals($resetRequest->getHashedToken(), $hashedVerifierToken->getHashedToken())) {
            throw new InvalidResetPasswordTokenException();
        }

        return $user;
    }

    private function findResetPasswordRequest(string $token): ?ResetPasswordRequestInterface
    {
        $selector = substr($token, 0, self::SELECTOR_LENGTH);

        return $this->repository->findLessorResetPasswordRequest($selector);
    }

    /**
     * {@inheritdoc}
     *
     * @throws InvalidResetPasswordTokenException
     */
    public function removeResetRequest(string $fullToken): void
    {
        $request = $this->findResetPasswordRequest($fullToken);

        if (null === $request) {
            throw new InvalidResetPasswordTokenException();
        }

        $this->repository->removeResetPasswordRequest($request);
    }
}
