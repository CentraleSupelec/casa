<?php

namespace App\Entity;

use App\Repository\PointOfInterestRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PointOfInterestRepository::class)]
class PointOfInterest
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 80)]
    #[Assert\Choice(callback: ['App\Constants', 'getPointsOfInterestCategories'])]
    #[Assert\NotNull]
    private ?string $category = null;

    #[ORM\Column(type: 'string', length: 80)]
    #[Assert\NotNull]
    private ?string $label = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: HousingGroup::class, inversedBy: 'pointsOfInterest')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?HousingGroup $housingGroup = null;

    public function __toString(): string
    {
        return sprintf('[%s] %s', $this->getHousingGroup(), $this->getLabel());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

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

    public function getHousingGroup(): ?HousingGroup
    {
        return $this->housingGroup;
    }

    public function setHousingGroup(?HousingGroup $housingGroup): self
    {
        $this->housingGroup = $housingGroup;

        return $this;
    }
}
