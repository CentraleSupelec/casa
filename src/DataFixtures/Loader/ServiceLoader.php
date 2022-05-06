<?php

namespace App\DataFixtures\Loader;

use App\Entity\Service;
use Exception;

class ServiceLoader extends AbstractLoader
{
    public function load()
    {
        $services = $this->getEntity();

        foreach ($services as $value) {
            $newService = new Service();

            $newService->setLabel($value['label']);
            try {
                $newService->setPicture($value['picture']);
            } catch (Exception $ex) {
                // No picture
            }

            $this->getManager()->persist($newService);
        }

        $this->getManager()->flush();
    }
}
