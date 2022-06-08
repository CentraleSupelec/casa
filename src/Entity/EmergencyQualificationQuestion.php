<?php

namespace App\Entity;

use App\Repository\EmergencyQualificationQuestionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EmergencyQualificationQuestionRepository::class)]
class EmergencyQualificationQuestion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotNull]
    private ?string $translationLabel = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $description = null;

    public function __toString(): string
    {
        return sprintf('[%s] %s', $this->getTranslationLabel(), $this->getDescription());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getTranslationLabel(): ?string
    {
        return $this->translationLabel;
    }

    public function setTranslationLabel(?string $translationLabel): self
    {
        $this->translationLabel = $translationLabel;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
