<?php

namespace App\DataFixtures\Loader;

use App\DataFixtures\Factory\LoaderFactory;
use App\Entity\ParentSchool;
use App\Entity\School;

class SchoolLoader extends AbstractLoader
{
    public function load()
    {
        $schools = $this->getEntity();

        foreach ($schools as $value) {
            $newSchool = new School();
            $newSchool->setName($value['name']);

            $repo = $this->getManager()->getRepository(ParentSchool::class);
            $newSchool->setParentSchool(
                    $repo->findOneBy(['name' => $value['parentStructure']]));

            $newSchool->setCampus($value['campus']);
            $newSchool->setAcronym($value['acronym']);
            $newSchool->setWebsiteUrl($value['websiteUrl']);

            $address = LoaderFactory::getLoader($this->getManager(), 'address', $value)->load();

            $newSchool->setAddress($address);

            $this->getManager()->persist($newSchool);
        }

        $this->getManager()->flush();
    }
}
