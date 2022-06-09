<?php

namespace App\Service;

use App\Model\PsuhUserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function updateUser(PsuhUserInterface $user): void
    {
        $this->hashPassword($user);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function hashPassword(PsuhUserInterface $user): void
    {
        $plaintextPassword = $user->getPlainPassword();
        if (0 === strlen($plaintextPassword)) {
            return;
        }

        $hashedPassword = $this->passwordHasher->hashPassword($user, $plaintextPassword);
        $user->setPassword($hashedPassword)->eraseCredentials();
    }
}
