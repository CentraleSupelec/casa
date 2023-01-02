<?php

namespace App\Entity;

use App\Model\TranslatableInterface;
use App\Repository\LeaseTypeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LeaseTypeRepository::class)]
class LeaseType implements TranslatableInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $labelFr;

    #[ORM\Column(type: 'string', length: 255)]
    private $labelEn;

    public function __toString(): string
    {
        return $this->getLabelFr();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabelFr(): ?string
    {
        return $this->labelFr;
    }

    public function setLabelFr(string $labelFr): self
    {
        $this->labelFr = $labelFr;

        return $this;
    }

    public function getLabelEn(): ?string
    {
        return $this->labelEn;
    }

    public function setLabelEn(string $labelEn): self
    {
        $this->labelEn = $labelEn;

        return $this;
    }
}
