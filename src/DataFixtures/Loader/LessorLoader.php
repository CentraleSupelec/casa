<?php

namespace App\DataFixtures\Loader;

use App\Entity\Lessor;

class LessorLoader extends AbstractLoader
{
    public function load()
    {
        $lessors = $this->getEntity();

        foreach ($lessors as $value) {
            $newLessor = new Lessor();
            $newLessor->setName($value['name']);
            $this->getManager()->persist($newLessor);
        }

        $this->getManager()->flush();
    }
}
