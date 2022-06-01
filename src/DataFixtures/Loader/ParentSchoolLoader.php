<?php

namespace App\DataFixtures\Loader;

use App\Entity\ParentSchool;

class ParentSchoolLoader extends AbstractLoader
{
    public function load()
    {
        $parentSchools = $this->getEntity();

        foreach ($parentSchools as $value) {
            $newParentSchool = new ParentSchool();
            $newParentSchool->setName($value['name']);

            $this->getManager()->persist($newParentSchool);
        }

        $this->getManager()->flush();
    }
}
