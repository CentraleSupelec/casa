<?php

namespace App\Entity;

use App\Model\TranslatableInterface;
use App\Repository\StayDurationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StayDurationRepository::class)]
class StayDuration implements TranslatableInterface
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

    /**
     * Get the value of labelFr.
     */
    public function getLabelFr(): ?string
    {
        return $this->labelFr;
    }

    /**
     * Set the value of labelFr.
     *
     * @return self
     */
    public function setLabelFr($labelFr)
    {
        $this->labelFr = $labelFr;

        return $this;
    }

    /**
     * Get the value of labelEn.
     */
    public function getLabelEn(): ?string
    {
        return $this->labelEn;
    }

    /**
     * Set the value of labelEn.
     *
     * @return self
     */
    public function setLabelEn($labelEn)
    {
        $this->labelEn = $labelEn;

        return $this;
    }
}
