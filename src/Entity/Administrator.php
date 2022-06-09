<?php

namespace App\Entity;

use App\Model\PsuhUserInterface;
use App\Repository\AdministratorRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AdministratorRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class Administrator implements PsuhUserInterface
{
    use TimestampableEntity;

    public const ROLE_DEFAULT = 'ROLE_ADMIN';

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?UuidV4 $id = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\NotNull]
    private ?string $email = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [self::ROLE_DEFAULT];

    #[ORM\Column(type: 'string')]
    private string $password;

    private ?string $plainPassword = null;

    #[ORM\Column(type: 'boolean')]
    #[Assert\NotNull]
    private bool $enabled = false;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $lastLoginAt = null;

    public function __toString(): string
    {
        return $this->getEmail();
    }

    public function getId(): ?UuidV4
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = self::ROLE_DEFAULT;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getVerified(): bool
    {
        return true;
    }

    public function setVerified(bool $verified): self
    {
        return $this;
    }

    public function getLastLoginAt(): ?\DateTimeInterface
    {
        return $this->lastLoginAt;
    }

    public function setLastLoginAt(?\DateTimeInterface $lastLoginAt): self
    {
        $this->lastLoginAt = $lastLoginAt;

        return $this;
    }

    public function setVerificationToken(?string $verificationToken): PsuhUserInterface
    {
        return $this;
    }

    public function getVerificationToken(): ?string
    {
        return null;
    }

    public function eraseCredentials(): self
    {
        $this->plainPassword = null;

        return $this;
    }
}
