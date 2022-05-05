<?php

namespace App\DataFixtures\Loader;

use App\Entity\Address;

class AddressLoader extends AbstractLoader
{
    public function load(): Address
    {
        $address = $this->getEntity();

        $newAddress = new Address();

        foreach ($address as $key => $value) {
            $setter = 'set'.$key;
            $newAddress->$setter($value);
        }

        return $newAddress;
    }
}
