<?php

namespace App\Entity;

use App\Repository\SchoolEmergencyQualificationQuestionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SchoolEmergencyQualificationQuestionRepository::class)]
#[UniqueEntity(fields: ['question', 'school'], message: 'This question has already been parametrized for this school')]
class SchoolEmergencyQualificationQuestion
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?string $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Choice(callback: ['App\Constants', 'getEmergencyQualificationQuestions'])]
    #[Assert\NotNull]
    private ?string $question = null;

    #[ORM\ManyToOne(targetEntity: School::class, inversedBy: 'schoolEmergencyQualificationQuestions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?School $school = null;

    #[ORM\Column(type: 'array', nullable: true)]
    private ?array $contactList = [];

    public function __toString(): string
    {
        return sprintf('[%s] %s', $this->getSchool(), $this->getQuestion());
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(?string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getSchool(): ?School
    {
        return $this->school;
    }

    public function setSchool(?School $school): self
    {
        $this->school = $school;

        return $this;
    }

    public function getContactList(): ?array
    {
        return $this->contactList;
    }

    public function setContactList(?array $contactList): self
    {
        $this->contactList = $contactList;

        return $this;
    }
}
