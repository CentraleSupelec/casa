<?php

namespace App\DataFixtures\Loader;

use App\Entity\Equipment;
use Exception;

class EquipmentLoader extends AbstractLoader
{
    public function load()
    {
        $equipment = $this->getEntity();

        foreach ($equipment as $value) {
            $newEquipment = new Equipment();

            $newEquipment->setLabel($value['label']);
            try {
                $newEquipment->setPicture($value['picture']);
            } catch (Exception $ex) {
                // No picture
            }

            $this->getManager()->persist($newEquipment);
        }

        $this->getManager()->flush();
    }
}
