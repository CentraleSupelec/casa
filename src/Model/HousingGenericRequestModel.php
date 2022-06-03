<?php

namespace App\Model;

use App\Entity\Student;

class HousingGenericRequestModel
{
    private string $body;

    private Student $student;

    public function __construct(string $body, Student $student)
    {
        $this->body = $body;
        $this->student = $student;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getStudent(): Student
    {
        return $this->student;
    }

    public function setStudent(Student $student): self
    {
        $this->student = $student;

        return $this;
    }
}
