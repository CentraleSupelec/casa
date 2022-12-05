<?php

namespace App\Entity;

use App\Repository\LessorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LessorRepository::class)]
class Lessor
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?string $id = null;

    #[ORM\Column(type: 'string', length: 80)]
    #[Assert\NotNull]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'lessor', targetEntity: LessorAdminUser::class)]
    private $lessorAdminUsers;

    public function __construct()
    {
        $this->lessorAdminUsers = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, LessorAdminUser>
     */
    public function getLessorAdminUsers(): Collection
    {
        return $this->lessorAdminUsers;
    }

    public function addLessorAdminUser(LessorAdminUser $lessorAdminUser): self
    {
        if (!$this->lessorAdminUsers->contains($lessorAdminUser)) {
            $this->lessorAdminUsers[] = $lessorAdminUser;
            $lessorAdminUser->setLessor($this);
        }

        return $this;
    }

    public function removeLessorAdminUser(LessorAdminUser $lessorAdminUser): self
    {
        if ($this->lessorAdminUsers->removeElement($lessorAdminUser)) {
            // set the owning side to null (unless already changed)
            if ($lessorAdminUser->getLessor() === $this) {
                $lessorAdminUser->setLessor(null);
            }
        }

        return $this;
    }
}
