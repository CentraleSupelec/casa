<?php

namespace App\Entity;

use App\Repository\HousingGroupServiceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: HousingGroupServiceRepository::class)]
class HousingGroupService
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?string $id = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $isOptional = null;

    #[ORM\ManyToOne(targetEntity: Service::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?Service $service = null;

    #[ORM\ManyToOne(targetEntity: HousingGroup::class, inversedBy: 'housingGroupServices')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?HousingGroup $housingGroup = null;

    public function __toString(): string
    {
        return $this->getIsOptional()
            ? sprintf('[%s] %s (en option)', $this->getHousingGroup(), $this->service->getLabel())
            : sprintf('[%s] %s (inclus)', $this->getHousingGroup(), $this->service->getLabel())
            ;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getIsOptional(): ?bool
    {
        return $this->isOptional;
    }

    public function setIsOptional(?bool $isOptional): self
    {
        $this->isOptional = $isOptional;

        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): self
    {
        $this->service = $service;

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
