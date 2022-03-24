<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Embeddable]
class Address
{
    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\Choice(callback: ['App\Constants', 'getAddressCountries'])]
    #[Assert\NotNull]
    private ?string $country = null;

    #[ORM\Column(type: 'string', length: 10)]
    #[Assert\NotNull]
    private ?string $postalCode = null;

    #[ORM\Column(type: 'string', length: 80)]
    #[Assert\NotNull]
    private ?string $city = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotNull]
    private ?string $street = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $streetDetail = null;

    public function __toString(): string
    {
        return sprintf(
            '%s, %s %s, %s', $this->getStreet(), $this->getPostalCode(), $this->getCity(), $this->getCountry()
        );
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getStreetDetail(): ?string
    {
        return $this->streetDetail;
    }

    public function setStreetDetail(?string $streetDetail): self
    {
        $this->streetDetail = $streetDetail;

        return $this;
    }
}
