<?php

namespace App\Entity;

use App\Repository\HousingGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: HousingGroupRepository::class)]
class HousingGroup
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

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

    public function __construct()
    {
        $this->address = new Address();
        $this->housings = new ArrayCollection();
    }

    public function __toString(): string
    {
        return sprintf('%s - %s', $this->getLessor(), $this->getName());
    }

    public function getId(): ?int
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
}
