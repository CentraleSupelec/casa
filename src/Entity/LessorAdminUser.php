<?php

namespace App\Entity;

use App\Model\PsuhUserInterface;
use App\Repository\LessorAdminUserRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LessorAdminUserRepository::class)]

class LessorAdminUser implements PsuhUserInterface
{
    use TimestampableEntity;

    public const ROLE_DEFAULT = 'ROLE_LESSOR_ADMIN';

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?UuidV4 $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotNull]
    private $email = null;

    #[ORM\Column(type: 'string')]
    private $password;

    private ?string $plainPassword = null;

    #[ORM\Column(type: 'boolean')]
    #[Assert\NotNull]
    private $enabled = false;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $lastLoginAt = null;

    #[ORM\Column(type: 'string', length: 255)]
    private $lastName;

    #[ORM\Column(type: 'string', length: 255)]
    private $firstName;

    #[ORM\ManyToOne(targetEntity: Lessor::class, inversedBy: 'lessorAdminUsers')]
    #[ORM\JoinColumn(nullable: false)]
    private $lessor;

    #[ORM\Column(type: 'json')]
    private array $roles = [self::ROLE_DEFAULT];

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $verificationToken;

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

    public function setEmail(string $email): self
    {
        $this->email = $email;

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

    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLessor(): ?Lessor
    {
        return $this->lessor;
    }

    public function setLessor(?Lessor $lessor): self
    {
        $this->lessor = $lessor;

        return $this;
    }

    /**
     *   PsuhUserInterface.
     * */
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

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

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
        $this->verificationToken = $verificationToken;

        return $this;
    }

    public function getVerificationToken(): ?string
    {
        return $this->verificationToken;
    }

    public function eraseCredentials(): self
    {
        $this->plainPassword = null;

        return $this;
    }
}
