<?php

namespace App\Service;

use App\Model\PsuhUserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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

    public function updateUser(PsuhUserInterface $user): void
    {
        $this->hashPassword($user);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    private function hashPassword(PsuhUserInterface $user): void
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
