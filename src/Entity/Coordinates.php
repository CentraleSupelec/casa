<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Coordinates
{
    #[ORM\Column(type: 'float', length: 30, nullable: true)]
    private ?float $latitude = null;

    #[ORM\Column(type: 'float', length: 30, nullable: true)]
    private ?float $longitude = null;

    public function __toString(): string
    {
        return '['.$this->latitude.','.$this->longitude.']';
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }
}
