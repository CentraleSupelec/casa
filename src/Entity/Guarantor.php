<?php

namespace App\Entity;

use App\Model\TranslatableInterface;
use App\Repository\GuarantorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GuarantorRepository::class)]
class Guarantor implements TranslatableInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $labelFr;

    #[ORM\Column(type: 'string', length: 255)]
    private string $labelEn;

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
