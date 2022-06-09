<?php

namespace App\Model;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

interface PsuhUserInterface extends UserInterface, PasswordAuthenticatedUserInterface
{
    public function getEnabled(): bool;

    public function setEnabled(bool $enabled): self;

    public function getPlainPassword(): ?string;

    public function setPassword(string $password): self;

    public function getVerified(): bool;

    public function setVerified(bool $verified): self;

    public function setVerificationToken(?string $verificationToken): self;

    public function getVerificationToken(): ?string;

    public function getLastLoginAt(): ?\DateTimeInterface;

    public function setLastLoginAt(?\DateTimeInterface $lastLoginAt): self;
}
