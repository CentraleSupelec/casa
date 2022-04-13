<?php

namespace App\Tests\Entity;

use App\Entity\Housing;
use App\Entity\HousingGroup;
use App\Tests\utils\FixturesProvider;
use App\Tests\utils\IntegrationTestCase;

class HousingGroupTest extends IntegrationTestCase
{
    public function testDeleteHousingGroupShouldDeleteRelatedHousings()
    {
        $lessor = FixturesProvider::getArpejLessor();

        $housingGroup = FixturesProvider::getPalaiseauHousingGroupNoLessor();
        $housingGroup->setLessor($lessor);

        $housing = FixturesProvider::getT1HousingNoHousingGroup();
        $housingGroup->addHousing($housing);

        $this->entityManager->persist($lessor);
        $this->entityManager->persist($housingGroup);
        $this->entityManager->persist($housing);

        $this->entityManager->flush();

        $this->entityManager->remove($housingGroup);
        $this->entityManager->flush();

        $housingGroupCount = $this->entityManager
            ->getRepository(HousingGroup::class)
            ->count([])
        ;
        $housingCount = $this->entityManager
            ->getRepository(Housing::class)
            ->count([])
        ;

        $this->assertEquals(0, $housingCount);
        $this->assertEquals(0, $housingGroupCount);
    }
}
