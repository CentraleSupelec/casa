<?php

namespace App\Service;

use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;

class StudentService
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function updateWith(Student $student)
    {
        $existingStudent = $this->entityManager
            ->getRepository(Student::class)
            ->find($student->getId());

        if (!$existingStudent) {
            return;
        }

        $existingStudent->setFirstName($student->getFirstName());
        $existingStudent->setLastName($student->getLastName());
        $existingStudent->setBirthdate($student->getBirthdate());
        $existingStudent->setEmail($student->getEmail());
        $existingStudent->setSocialScholarship($student->getSocialScholarship());
        $existingStudent->setReducedMobility($student->getReducedMobility());
        $existingStudent->setSchool($student->getSchool());

        $this->entityManager->flush();
    }
}
