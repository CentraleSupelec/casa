<?php

namespace App\Entity;

use App\Model\PsuhUserInterface;
use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class Student implements PsuhUserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableEntity;

    public const ROLE_DEFAULT = 'ROLE_STUDENT';

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?string $id = null;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
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

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $verificationToken;

    #[ORM\Column(type: 'boolean')]
    #[Assert\NotNull]
    private bool $verified = false;

    #[ORM\ManyToMany(targetEntity: Housing::class, inversedBy: 'students')]
    private Collection $bookmarks;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $birthdate = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $reducedMobility = null;

    #[ORM\ManyToOne(targetEntity: School::class)]
    private ?School $school = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $socialScholarship = null;

    public function __construct()
    {
        $this->bookmarks = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getEmail();
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    public function getId(): ?string
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

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = self::ROLE_DEFAULT;

        return array_unique($roles);
    }

    public function setRoles(?array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
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

    public function getLastLoginAt(): ?\DateTimeInterface
    {
        return $this->lastLoginAt;
    }

    public function setLastLoginAt(?\DateTimeInterface $lastLoginAt): self
    {
        $this->lastLoginAt = $lastLoginAt;

        return $this;
    }

    public function getVerificationToken(): ?string
    {
        return $this->verificationToken;
    }

    public function setVerificationToken(?string $verificationToken): self
    {
        $this->verificationToken = $verificationToken;

        return $this;
    }

    public function getVerified(): bool
    {
        return $this->verified;
    }

    public function setVerified(bool $verified): self
    {
        $this->verified = $verified;

        return $this;
    }

    /**
     * @return Collection<int, Housing>
     */
    public function getBookmarks(): Collection
    {
        return $this->bookmarks;
    }

    public function addBookmark(Housing $bookmark): self
    {
        if (!$this->bookmarks->contains($bookmark)) {
            $this->bookmarks[] = $bookmark;
        }

        return $this;
    }

    public function removeBookmark(Housing $bookmark): self
    {
        $this->bookmarks->removeElement($bookmark);

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $name): self
    {
        $this->lastName = $name;

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

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(?\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getReducedMobility(): ?bool
    {
        return $this->reducedMobility;
    }

    public function setReducedMobility(bool $reducedMobility): self
    {
        $this->reducedMobility = $reducedMobility;

        return $this;
    }

    public function getSchool(): ?school
    {
        return $this->school;
    }

    public function setSchool(?school $school): self
    {
        $this->school = $school;

        return $this;
    }

    public function getSocialScholarship(): ?bool
    {
        return $this->socialScholarship;
    }

    public function setSocialScholarship(?bool $socialScholarship): self
    {
        $this->socialScholarship = $socialScholarship;

        return $this;
    }
}
