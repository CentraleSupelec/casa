<?php

namespace App\Service;

use App\Model\PsuhUserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class UserService
{
    private UserPasswordHasherInterface $passwordHasher;

    private EntityManagerInterface $entityManager;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ) {
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;
    }

    public function updateUser(PsuhUserInterface $user)
    {
        if ($user instanceof PasswordAuthenticatedUserInterface) {
            $this->hashPassword($user);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function hashPassword(PsuhUserInterface $user)
    {
        $plaintextPassword = $user->getPlainPassword();
        if (0 === strlen($plaintextPassword)) {
            return;
        }

        $hashedPassword = $this->passwordHasher->hashPassword($user, $plaintextPassword);
        $user->setPassword($hashedPassword);
        $user->eraseCredentials();
    }
}
