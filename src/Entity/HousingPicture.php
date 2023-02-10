<?php

namespace App\Entity;

use App\Repository\HousingPictureRepository;
use App\Validator\Constraints as AppAssert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: HousingPictureRepository::class)]
#[Vich\Uploadable]
class HousingPicture
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?string $id = null;

    #[ORM\Column(type: 'string', length: 80, nullable: true)]
    private ?string $label = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $picture = null;

    #[Vich\UploadableField(mapping: 'housing_picture', fileNameProperty: 'picture')]
    #[AppAssert\ClamAvConstraint]
    #[Assert\File(maxSize: '4M', mimeTypes: ['image/jpeg', 'image/png'])]
    private ?File $file = null;

    #[ORM\ManyToOne(targetEntity: Housing::class, inversedBy: 'pictures')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?Housing $housing = null;

    public function __toString(): string
    {
        return $this->getLabel() ?: sprintf('Photo #%s sans titre', $this->getId());
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function setFile(?File $file = null): void
    {
        $this->file = $file;

        if (null !== $file) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function getHousing(): ?Housing
    {
        return $this->housing;
    }

    public function setHousing(?Housing $housing): self
    {
        $this->housing = $housing;

        return $this;
    }
}
