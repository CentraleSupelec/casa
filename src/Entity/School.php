<?php

namespace App\Entity;

use App\Repository\SchoolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SchoolRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'There is already a school with this name')]
class School
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?string $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotNull]
    private ?string $name = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $idGovernment = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $websiteUrl = null;

    #[ORM\Embedded(class: Address::class)]
    private Address $address;

    #[ORM\ManyToMany(targetEntity: HousingGroup::class, mappedBy: 'schools')]
    private Collection $housingGroups;

    #[ORM\Column(type: 'string', length: 32, nullable: true)]
    private ?string $acronym = null;

    #[ORM\Column(type: 'string', length: 64)]
    #[Assert\NotNull]
    private ?string $campus = null;

    #[ORM\ManyToMany(targetEntity: SchoolCriterion::class, mappedBy: 'schools')]
    private Collection $schoolCriteria;

    public function __construct()
    {
        $this->address = new Address();
        $this->housingGroups = new ArrayCollection();
        $this->schoolCriteria = new ArrayCollection();
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

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIdGovernment(): ?string
    {
        return $this->idGovernment;
    }

    public function setIdGovernment(?string $idGovernment): self
    {
        $this->idGovernment = $idGovernment;

        return $this;
    }

    public function getWebsiteUrl(): ?string
    {
        return $this->websiteUrl;
    }

    public function setWebsiteUrl(?string $websiteUrl): self
    {
        $this->websiteUrl = $websiteUrl;

        return $this;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, HousingGroup>
     */
    public function getHousingGroups(): Collection
    {
        return $this->housingGroups;
    }

    public function addHousingGroup(HousingGroup $housingGroup): self
    {
        if (!$this->housingGroups->contains($housingGroup)) {
            $this->housingGroups[] = $housingGroup;
            $housingGroup->addSchool($this);
        }

        return $this;
    }

    public function removeHousingGroup(HousingGroup $housingGroup): self
    {
        if ($this->housingGroups->removeElement($housingGroup)) {
            $housingGroup->removeSchool($this);
        }

        return $this;
    }

    public function getAcronym(): ?string
    {
        return $this->acronym;
    }

    public function setAcronym(?string $acronym): self
    {
        $this->acronym = $acronym;

        return $this;
    }

    public function getCampus(): ?string
    {
        return $this->campus;
    }

    public function setCampus(?string $campus): self
    {
        $this->campus = $campus;

        return $this;
    }
}
