<?php

namespace App\Entity;

use App\Repository\HousingGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: HousingGroupRepository::class)]
class HousingGroup
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

    #[ORM\Embedded(class: Address::class)]
    private Address $address;

    #[ORM\OneToMany(mappedBy: 'housingGroup', targetEntity: Housing::class, orphanRemoval: true)]
    private Collection $housings;

    #[ORM\ManyToOne(targetEntity: Lessor::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?Lessor $lessor = null;

    #[ORM\OneToMany(mappedBy: 'housingGroup', targetEntity: HousingGroupService::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $housingGroupServices;

    #[ORM\ManyToMany(targetEntity: Equipment::class)]
    private Collection $equipments;

    #[ORM\OneToMany(mappedBy: 'housingGroup', targetEntity: PointOfInterest::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $pointsOfInterest;

    #[ORM\ManyToMany(targetEntity: Guarantor::class, cascade: ['persist'])]
    private Collection $possibleGuarantor;

    public function __construct()
    {
        $this->address = new Address();
        $this->housings = new ArrayCollection();
        $this->housingGroupServices = new ArrayCollection();
        $this->equipments = new ArrayCollection();
        $this->pointsOfInterest = new ArrayCollection();
        $this->possibleGuarantor = new ArrayCollection();
    }

    public function __toString(): string
    {
        return sprintf('%s - %s', $this->getLessor(), $this->getName());
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
     * @return Collection<int, Housing>
     */
    public function getHousings(): Collection
    {
        return $this->housings;
    }

    public function addHousing(Housing $housing): self
    {
        if (!$this->housings->contains($housing)) {
            $this->housings[] = $housing;
            $housing->setHousingGroup($this);
        }

        return $this;
    }

    public function removeHousing(Housing $housing): self
    {
        if ($this->housings->removeElement($housing)) {
            // set the owning side to null (unless already changed)
            if ($housing->getHousingGroup() === $this) {
                $housing->setHousingGroup(null);
            }
        }

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
     * @return Collection<int, HousingGroupService>
     */
    public function getHousingGroupServices(): Collection
    {
        return $this->housingGroupServices;
    }

    public function addHousingGroupService(HousingGroupService $housingGroupService): self
    {
        if (!$this->housingGroupServices->contains($housingGroupService)) {
            $this->housingGroupServices[] = $housingGroupService;
            $housingGroupService->setHousingGroup($this);
        }

        return $this;
    }

    public function removeHousingGroupService(HousingGroupService $housingGroupService): self
    {
        if ($this->housingGroupServices->removeElement($housingGroupService)) {
            // set the owning side to null (unless already changed)
            if ($housingGroupService->getHousingGroup() === $this) {
                $housingGroupService->setHousingGroup(null);
            }
        }

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
     * @return Collection<int, PointOfInterest>
     */
    public function getPointsOfInterest(): Collection
    {
        return $this->pointsOfInterest;
    }

    public function addPointsOfInterest(PointOfInterest $pointsOfInterest): self
    {
        if (!$this->pointsOfInterest->contains($pointsOfInterest)) {
            $this->pointsOfInterest[] = $pointsOfInterest;
            $pointsOfInterest->setHousingGroup($this);
        }

        return $this;
    }

    public function removePointsOfInterest(PointOfInterest $pointsOfInterest): self
    {
        if ($this->pointsOfInterest->removeElement($pointsOfInterest)) {
            // set the owning side to null (unless already changed)
            if ($pointsOfInterest->getHousingGroup() === $this) {
                $pointsOfInterest->setHousingGroup(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Guarantor>
     */
    public function getPossibleGuarantor(): Collection
    {
        return $this->possibleGuarantor;
    }

    public function addPossibleGuarantor(Guarantor $possibleGuarantor): self
    {
        if (!$this->possibleGuarantor->contains($possibleGuarantor)) {
            $this->possibleGuarantor[] = $possibleGuarantor;
        }

        return $this;
    }

    public function removePossibleGuarantor(Guarantor $possibleGuarantor): self
    {
        $this->possibleGuarantor->removeElement($possibleGuarantor);

        return $this;
    }
}
