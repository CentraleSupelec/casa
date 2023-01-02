<?php

namespace App\Entity;

use App\Repository\HousingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: HousingRepository::class)]
class Housing
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?string $id = null;

    #[ORM\Column(type: 'text', length: 65535, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Url]
    private ?string $redirectLink = null;

    #[ORM\Column(type: 'boolean')]
    #[Assert\NotNull]
    private ?bool $available = null;

    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\Choice(callback: ['App\Constants', 'getHousingTypes'])]
    #[Assert\NotNull]
    private ?string $type = null;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotNull]
    private ?int $areaMin = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $areaMax = null;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotNull]
    private ?int $rentMin = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $rentMax = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $chargesMin = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $chargesMax = null;

    #[ORM\Column(type: 'boolean')]
    #[Assert\NotNull]
    private ?bool $chargesIncluded = null;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotNull]
    private ?int $applicationFeeMin = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $applicationFeeMax = null;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotNull]
    private ?int $securityDepositMin = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $securityDepositMax = null;

    #[ORM\Column(type: 'string', length: 80)]
    #[Assert\Choice(callback: ['App\Constants', 'getHousingLivingModes'])]
    #[Assert\NotNull]
    private ?string $livingMode = null;

    #[ORM\Column(type: 'boolean')]
    #[Assert\NotNull]
    private ?bool $accessibility = null;

    #[ORM\Column(type: 'boolean')]
    #[Assert\NotNull]
    private ?bool $smoking = null;

    #[ORM\Column(type: 'boolean')]
    #[Assert\NotNull]
    private ?bool $animalsAllowed = null;

    #[ORM\ManyToOne(targetEntity: HousingGroup::class, inversedBy: 'housings')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?HousingGroup $housingGroup = null;

    #[ORM\OneToMany(mappedBy: 'housing', targetEntity: HousingPicture::class, orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $pictures;

    #[ORM\OneToMany(mappedBy: 'housing', targetEntity: SocialScholarshipCriterion::class, orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $socialScholarshipCriteria;

    #[ORM\OneToMany(mappedBy: 'housing', targetEntity: SchoolCriterion::class, orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $schoolCriteria;

    #[ORM\ManyToMany(targetEntity: Student::class, mappedBy: 'bookmarks')]
    private Collection $students;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $quantity = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $occupants = null;

    #[ORM\ManyToMany(targetEntity: Equipment::class)]
    private Collection $equipments;

    #[ORM\ManyToMany(targetEntity: StayDuration::class, cascade: ['persist'])]
    private Collection $stayDurations;

    #[ORM\ManyToMany(targetEntity: OccupationMode::class, cascade: ['persist'])]
    private Collection $occupationModes;

    #[ORM\Column(type: 'boolean')]
    private ?bool $aplAgreement = false;

    #[ORM\ManyToMany(targetEntity: LeaseType::class, cascade: ['persist'])]
    private Collection $leaseType;

    public function __construct()
    {
        $this->pictures = new ArrayCollection();
        $this->socialScholarshipCriteria = new ArrayCollection();
        $this->schoolCriteria = new ArrayCollection();
        $this->students = new ArrayCollection();
        $this->equipments = new ArrayCollection();
        $this->stayDurations = new ArrayCollection();
        $this->occupationModes = new ArrayCollection();
        $this->leaseType = new ArrayCollection();
    }

    public function __toString(): string
    {
        return sprintf('%s - %s', $this->getHousingGroup(), $this->getType());
    }

    public function hasSocialScholarshipCriterion(): bool
    {
        $now = time();
        foreach ($this->getSocialScholarshipCriteria() as $criterion) {
            if (date_timestamp_get($criterion->getStartDate()) < $now and $now < date_timestamp_get($criterion->getEndDate())) {
                return true;
            }
        }

        return false;
    }

    public function hasSchoolCriterion(): bool
    {
        $now = time();
        foreach ($this->getSchoolCriteria() as $criterion) {
            if (date_timestamp_get($criterion->getStartDate()) < $now and $now < date_timestamp_get($criterion->getEndDate())) {
                return true;
            }
        }

        return false;
    }

    public function getId(): ?string
    {
        return $this->id;
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

    public function getRedirectLink(): ?string
    {
        return $this->redirectLink;
    }

    public function setRedirectLink(?string $redirectLink): self
    {
        $this->redirectLink = $redirectLink;

        return $this;
    }

    public function getAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(?bool $available): self
    {
        $this->available = $available;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAreaMin(): ?int
    {
        return $this->areaMin;
    }

    public function setAreaMin(?int $areaMin): self
    {
        $this->areaMin = $areaMin;

        return $this;
    }

    public function getAreaMax(): ?int
    {
        return $this->areaMax;
    }

    public function setAreaMax(?int $areaMax): self
    {
        $this->areaMax = $areaMax;

        return $this;
    }

    public function getRentMin(): ?int
    {
        return $this->rentMin;
    }

    public function setRentMin(?int $rentMin): self
    {
        $this->rentMin = $rentMin;

        return $this;
    }

    public function getRentMax(): ?int
    {
        return $this->rentMax;
    }

    public function setRentMax(?int $rentMax): self
    {
        $this->rentMax = $rentMax;

        return $this;
    }

    public function getChargesMin(): ?int
    {
        return $this->chargesMin;
    }

    public function setChargesMin(?int $chargesMin): self
    {
        $this->chargesMin = $chargesMin;

        return $this;
    }

    public function getChargesMax(): ?int
    {
        return $this->chargesMax;
    }

    public function setChargesMax(?int $chargesMax): self
    {
        $this->chargesMax = $chargesMax;

        return $this;
    }

    public function getChargesIncluded(): ?bool
    {
        return $this->chargesIncluded;
    }

    public function setChargesIncluded(?bool $chargesIncluded): self
    {
        $this->chargesIncluded = $chargesIncluded;

        return $this;
    }

    public function getApplicationFeeMin(): ?int
    {
        return $this->applicationFeeMin;
    }

    public function setApplicationFeeMin(?int $applicationFeeMin): self
    {
        $this->applicationFeeMin = $applicationFeeMin;

        return $this;
    }

    public function getApplicationFeeMax(): ?int
    {
        return $this->applicationFeeMax;
    }

    public function setApplicationFeeMax(?int $applicationFeeMax): self
    {
        $this->applicationFeeMax = $applicationFeeMax;

        return $this;
    }

    public function getSecurityDepositMin(): ?int
    {
        return $this->securityDepositMin;
    }

    public function setSecurityDepositMin(?int $securityDepositMin): self
    {
        $this->securityDepositMin = $securityDepositMin;

        return $this;
    }

    public function getSecurityDepositMax(): ?int
    {
        return $this->securityDepositMax;
    }

    public function setSecurityDepositMax(?int $securityDepositMax): self
    {
        $this->securityDepositMax = $securityDepositMax;

        return $this;
    }

    public function getLivingMode(): ?string
    {
        return $this->livingMode;
    }

    public function setLivingMode(?string $livingMode): self
    {
        $this->livingMode = $livingMode;

        return $this;
    }

    public function getAccessibility(): ?bool
    {
        return $this->accessibility;
    }

    public function setAccessibility(?bool $accessibility): self
    {
        $this->accessibility = $accessibility;

        return $this;
    }

    public function getSmoking(): ?bool
    {
        return $this->smoking;
    }

    public function setSmoking(?bool $smoking): self
    {
        $this->smoking = $smoking;

        return $this;
    }

    public function getAnimalsAllowed(): ?bool
    {
        return $this->animalsAllowed;
    }

    public function setAnimalsAllowed(?bool $animalsAllowed): self
    {
        $this->animalsAllowed = $animalsAllowed;

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

    /**
     * @return Collection<int, HousingPicture>
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(HousingPicture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->setHousing($this);
        }

        return $this;
    }

    public function removePicture(HousingPicture $picture): self
    {
        if ($this->pictures->removeElement($picture)) {
            if ($picture->getHousing() === $this) {
                $picture->setHousing(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SocialScholarshipCriterion>
     */
    public function getSocialScholarshipCriteria(): Collection
    {
        return $this->socialScholarshipCriteria;
    }

    public function addSocialScholarshipCriterion(SocialScholarshipCriterion $socialScholarshipCriterion): self
    {
        if (!$this->socialScholarshipCriteria->contains($socialScholarshipCriterion)) {
            $this->socialScholarshipCriteria[] = $socialScholarshipCriterion;
            $socialScholarshipCriterion->setHousing($this);
        }

        return $this;
    }

    public function removeSocialScholarshipCriterion(SocialScholarshipCriterion $socialScholarshipCriterion): self
    {
        if ($this->socialScholarshipCriteria->removeElement($socialScholarshipCriterion)) {
            if ($socialScholarshipCriterion->getHousing() === $this) {
                $socialScholarshipCriterion->setHousing(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SchoolCriterion>
     */
    public function getSchoolCriteria(): Collection
    {
        return $this->schoolCriteria;
    }

    public function addSchoolCriterion(SchoolCriterion $schoolCriterion): self
    {
        if (!$this->schoolCriteria->contains($schoolCriterion)) {
            $this->schoolCriteria[] = $schoolCriterion;
            $schoolCriterion->setHousing($this);
        }

        return $this;
    }

    public function removeSchoolCriterion(SchoolCriterion $schoolCriterion): self
    {
        if ($this->schoolCriteria->removeElement($schoolCriterion)) {
            if ($schoolCriterion->getHousing() === $this) {
                $schoolCriterion->setHousing(null);
            }
        }

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getOccupants(): ?int
    {
        return $this->occupants;
    }

    public function setOccupants(?int $occupants): self
    {
        $this->occupants = $occupants;

        return $this;
    }

    /**
     * @return Collection<int, Equipment>
     */
    public function getEquipments(): Collection
    {
        return $this->equipments;
    }

    public function addEquipment(Equipment $equipment): self
    {
        if (!$this->equipments->contains($equipment)) {
            $this->equipments[] = $equipment;
        }

        return $this;
    }

    public function removeEquipment(Equipment $equipment): self
    {
        $this->equipments->removeElement($equipment);

        return $this;
    }

    /**
     * @return Collection<int, StayDuration>
     */
    public function getStayDurations(): Collection
    {
        return $this->stayDurations;
    }

    public function addStayDuration(StayDuration $stayDuration): self
    {
        if (!$this->stayDurations->contains($stayDuration)) {
            $this->stayDurations[] = $stayDuration;
        }

        return $this;
    }

    public function removeStayDuration(StayDuration $stayDuration): self
    {
        $this->stayDurations->removeElement($stayDuration);

        return $this;
    }

    /**
     * @return Collection<int, OccupationMode>
     */
    public function getOccupationModes(): Collection
    {
        return $this->occupationModes;
    }

    public function addOccupationMode(OccupationMode $OccupationMode): self
    {
        if (!$this->occupationModes->contains($OccupationMode)) {
            $this->occupationModes[] = $OccupationMode;
        }

        return $this;
    }

    public function removeOccupationMode(OccupationMode $OccupationMode): self
    {
        $this->occupationModes->removeElement($OccupationMode);

        return $this;
    }

    public function isAplAgreement(): ?bool
    {
        return $this->aplAgreement;
    }

    public function setAplAgreement(bool $aplAgreement): self
    {
        $this->aplAgreement = $aplAgreement;

        return $this;
    }

    /**
     * @return Collection<int, LeaseType>
     */
    public function getLeaseType(): Collection
    {
        return $this->leaseType;
    }

    public function addLeaseType(LeaseType $leaseType): self
    {
        if (!$this->leaseType->contains($leaseType)) {
            $this->leaseType[] = $leaseType;
        }

        return $this;
    }

    public function removeLeaseType(LeaseType $leaseType): self
    {
        $this->leaseType->removeElement($leaseType);

        return $this;
    }
}
