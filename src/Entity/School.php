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
#[UniqueEntity(fields: ['name', 'campus'], message: 'There is already a school with this name for this campus')]
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
    private ?string $housingServiceEmail = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $idGovernment = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $websiteUrl = null;

    #[ORM\Embedded(class: Address::class)]
    private Address $address;

    #[ORM\Column(type: 'string', length: 32, nullable: true)]
    private ?string $acronym = null;

    #[ORM\Column(type: 'string', length: 64)]
    #[Assert\NotNull]
    private ?string $campus = null;

    #[ORM\ManyToMany(targetEntity: SchoolCriterion::class, mappedBy: 'schools')]
    private Collection $schoolCriteria;

    #[ORM\ManyToOne(targetEntity: ParentSchool::class, inversedBy: 'schools')]
    #[ORM\JoinColumn(nullable: false)]
    private ParentSchool $parentSchool;

    #[ORM\OneToMany(mappedBy: 'school', targetEntity: SchoolEmergencyQualificationQuestion::class, orphanRemoval: true)]
    private Collection $schoolEmergencyQualificationQuestions;

    public function __construct()
    {
        $this->address = new Address();
        $this->schoolCriteria = new ArrayCollection();
        $this->schoolEmergencyQualificationQuestions = new ArrayCollection();
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

    public function getHousingServiceEmail(): ?string
    {
        return $this->housingServiceEmail;
    }

    public function setHousingServiceEmail(?string $housingServiceEmail): self
    {
        $this->housingServiceEmail = $housingServiceEmail;

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

    public function getParentSchool(): ?ParentSchool
    {
        return $this->parentSchool;
    }

    public function setParentSchool(?ParentSchool $parentSchool): self
    {
        $this->parentSchool = $parentSchool;

        return $this;
    }

    /**
     * @return Collection<int, SchoolEmergencyQualificationQuestion>
     */
    public function getSchoolEmergencyQualificationQuestions(): Collection
    {
        return $this->schoolEmergencyQualificationQuestions;
    }

    public function addSchoolEmergencyQualificationQuestion(SchoolEmergencyQualificationQuestion $schoolEmergencyQualificationQuestion): self
    {
        if (!$this->schoolEmergencyQualificationQuestions->contains($schoolEmergencyQualificationQuestion)) {
            $this->schoolEmergencyQualificationQuestions[] = $schoolEmergencyQualificationQuestion;
            $schoolEmergencyQualificationQuestion->setSchool($this);
        }

        return $this;
    }

    public function removeSchoolEmergencyQualificationQuestion(SchoolEmergencyQualificationQuestion $schoolEmergencyQualificationQuestion): self
    {
        if ($this->schoolEmergencyQualificationQuestions->removeElement($schoolEmergencyQualificationQuestion)) {
            // set the owning side to null (unless already changed)
            if ($schoolEmergencyQualificationQuestion->getSchool() === $this) {
                $schoolEmergencyQualificationQuestion->setSchool(null);
            }
        }

        return $this;
    }
}
