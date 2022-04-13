<?php

namespace App\Tests\Entity;

use App\Entity\HousingGroup;
use App\Entity\Lessor;
use App\Tests\utils\FixturesProvider;
use App\Tests\utils\IntegrationTestCase;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

class LessorTest extends IntegrationTestCase
{
    public function testCreate(): void
    {
        $lessor = new Lessor();
        $lessor->setName('ARPEJ');

        $this->entityManager->persist($lessor);
        $this->entityManager->flush();

        $actualLessor = $this->entityManager
            ->getRepository(Lessor::class)
            ->findOneBy(['name' => 'ARPEJ'])
        ;

        $this->assertEquals($lessor->getName(), $actualLessor->getName());
    }

    public function testDatabaseIsEmptyAtTestBegin(): void
    {
        $lessorCount = $this->entityManager
            ->getRepository(Lessor::class)
            ->count([])
        ;

        $this->assertEquals(0, $lessorCount);
    }

    public function testShouldNotDeleteIfHousingGroup(): void
    {
        $lessor = FixturesProvider::getArpejLessor();

        $housingGroup = FixturesProvider::getPalaiseauHousingGroupNoLessor();
        $housingGroup->setLessor($lessor);

        $this->entityManager->persist($lessor);
        $this->entityManager->persist($housingGroup);

        $this->entityManager->flush();

        $this->expectException(ForeignKeyConstraintViolationException::class);
        $this->entityManager->remove($lessor);
        $this->entityManager->flush();

        $lessorCount = $this->entityManager
            ->getRepository(Lessor::class)
            ->count([])
        ;
        $housingGroupCount = $this->entityManager
            ->getRepository(HousingGroup::class)
            ->count([])
        ;

        $this->assertEquals(1, $lessorCount);
        $this->assertEquals(1, $housingGroupCount);
    }

    public function testShouldDeleteIfNoHousingGroup(): void
    {
        $lessor = FixturesProvider::getArpejLessor();

        $housingGroup = FixturesProvider::getPalaiseauHousingGroupNoLessor();
        $housingGroup->setLessor($lessor);

        $this->entityManager->persist($lessor);
        $this->entityManager->persist($housingGroup);

        $this->entityManager->flush();

        $this->entityManager->remove($housingGroup);
        $this->entityManager->remove($lessor);
        $this->entityManager->flush();

        $lessorCount = $this->entityManager
            ->getRepository(Lessor::class)
            ->count([])
        ;
        $housingGroupCount = $this->entityManager
            ->getRepository(HousingGroup::class)
            ->count([])
        ;

        $this->assertEquals(0, $lessorCount);
        $this->assertEquals(0, $housingGroupCount);
    }
}
