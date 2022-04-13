<?php

namespace App\Tests\utils;

use App\Entity\Address;
use App\Entity\Housing;
use App\Entity\HousingGroup;
use App\Entity\Lessor;

class FixturesProvider
{
    public static function getArpejLessor(): Lessor
    {
        $lessor = new Lessor();
        $lessor->setName('ARPEJ');

        return $lessor;
    }

    public static function getPalaiseauHousingGroupNoLessor(): HousingGroup
    {
        $address = new Address();
        $address->setCountry('france');
        $address->setPostalCode('91120');
        $address->setCity('Palaiseau');
        $address->setStreet('47 rue de Vauhallan');

        $housingGroup = new HousingGroup();
        $housingGroup->setName('Résidence de Palaiseau');
        $housingGroup->setAddress($address);

        return $housingGroup;
    }

    public static function getT1HousingNoHousingGroup(): Housing
    {
        $housing = new Housing();
        $housing->setAvailable(true);
        $housing->setType('t1');
        $housing->setAreaMin(19);
        $housing->setRentMin(450);
        $housing->setChargesIncluded(false);
        $housing->setChargesMin(60);
        $housing->setApplicationFeeMin(80);
        $housing->setSecurityDepositMin(400);
        $housing->setAccessibility(false);
        $housing->setAnimalsAllowed(false);
        $housing->setSmoking(false);

        return $housing;
    }
}
