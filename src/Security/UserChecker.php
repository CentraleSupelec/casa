<?php

namespace App\Security;

use App\Constants;
use App\Model\PsuhUserInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserChecker implements UserCheckerInterface
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof PsuhUserInterface) {
            return;
        }

        if (!$user->getVerified()) {
            throw new CustomUserMessageAccountStatusException($this->translator->trans('authentication.log_in.not_verified'), [], Constants::NON_VERIFIED_ACCOUNT_ERROR_CODE);
        }

        if (!$user->getEnabled()) {
            throw new DisabledException();
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
    }
}
