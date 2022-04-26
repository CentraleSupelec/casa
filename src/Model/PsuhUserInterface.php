<?php

namespace App\Model;

use Symfony\Component\Security\Core\User\UserInterface;

interface PsuhUserInterface extends UserInterface
{
    public function getEnabled(): bool;

    public function setEnabled(bool $enabled): self;

    public function getVerified(): bool;

    public function setVerified(bool $verified): self;

    public function setVerificationToken(?string $verificationToken): self;

    public function getVerificationToken(): ?string;

    public function getLastLoginAt(): ?\DateTimeInterface;

    public function setLastLoginAt(?\DateTimeInterface $lastLoginAt): self;
}
