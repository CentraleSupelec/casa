<?php

namespace App\DataFixtures\Loader;

use App\DataFixtures\Factory\LoaderFactory;
use App\Entity\Housing;
use App\Entity\HousingGroup;
use App\Entity\Lessor;

class HousingGroupLoader extends AbstractLoader
{
    public function load()
    {
        $housingGroups = $this->getEntity();

        foreach ($housingGroups as $housingGroup) {
            $newHousingGroup = new HousingGroup();
            $newHousingGroup->setName($housingGroup['name']);

            $address = LoaderFactory::getLoader($this->getManager(), 'address', $housingGroup)->load();

            $newHousingGroup->setAddress($address);

            $lessor = $this->getManager()
                ->getRepository(Lessor::class)
                ->findOneBy(['name' => $housingGroup['lessor']]);

            $housings = $housingGroup['housings'];

            foreach ($housings as $housing) {
                $newHousing = new Housing();

                foreach ($housing as $key => $value) {
                    $setter = 'set'.$key;
                    $newHousing->$setter($value);
                }

                $newHousingGroup->addHousing($newHousing);
                $this->getManager()->persist($newHousing);
            }

            $newHousingGroup->setLessor($lessor);
            $this->getManager()->persist($newHousingGroup);
        }

        $this->getManager()->flush();
    }
}
