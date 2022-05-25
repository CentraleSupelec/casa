<?php

namespace App\Model;

use App\Entity\School;
use App\Entity\Student;

class StudentProfileCriteriaModel
{
    private bool $socialScholarship = false;

    private ?School $school = null;

    public function __construct(?Student $student)
    {
        if (null !== $student) {
            $this->socialScholarship = $student->getSocialScholarship() ?: false;

            if (null !== $student->getSchool()) {
                $this->school = $student->getSchool();
            }
        }
    }

    public function getSocialScholarship(): bool
    {
        return $this->socialScholarship;
    }

    public function setSocialScholarship(bool $socialScholarship): self
    {
        $this->socialScholarship = $socialScholarship;

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
}
