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

    #[ORM\Column(type: 'string', length: 512, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
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

    #[ORM\Column(type: 'string', length: 80)]
    #[Assert\Choice(callback: ['App\Constants', 'getHousingOccupationModes'])]
    #[Assert\NotNull]
    private ?string $occupationMode = null;

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

    #[ORM\OneToMany(mappedBy: 'housing', targetEntity: HousingPicture::class, orphanRemoval: true)]
    private Collection $pictures;

    public function __construct()
    {
        $this->pictures = new ArrayCollection();
    }

    public function __toString(): string
    {
        return sprintf('%s - %s', $this->getHousingGroup(), $this->getType());
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

    public function getOccupationMode(): ?string
    {
        return $this->occupationMode;
    }

    public function setOccupationMode(?string $occupationMode): self
    {
        $this->occupationMode = $occupationMode;

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
            // set the owning side to null (unless already changed)
            if ($picture->getHousing() === $this) {
                $picture->setHousing(null);
            }
        }

        return $this;
    }
}
