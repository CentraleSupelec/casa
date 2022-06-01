<?php

namespace App\DataFixtures\Factory;

use App\DataFixtures\Loader\AbstractLoader;
use App\DataFixtures\Loader\AddressLoader;
use App\DataFixtures\Loader\EquipmentLoader;
use App\DataFixtures\Loader\HousingGroupLoader;
use App\DataFixtures\Loader\LessorLoader;
use App\DataFixtures\Loader\ParentSchoolLoader;
use App\DataFixtures\Loader\SchoolLoader;
use App\DataFixtures\Loader\ServiceLoader;
use Doctrine\Persistence\ObjectManager;

class LoaderFactory
{
    public const loaders = [
        'lessors' => LessorLoader::class,
        'schools' => SchoolLoader::class,
        'parentSchools' => ParentSchoolLoader::class,
        'address' => AddressLoader::class,
        'housingGroups' => HousingGroupLoader::class,
        'services' => ServiceLoader::class,
        'equipments' => EquipmentLoader::class,
    ];

    public static function getLoader(ObjectManager $manager, string $entityName, array $entities): AbstractLoader
    {
        $classLoader = LoaderFactory::loaders[$entityName];

        return new $classLoader($manager, $entities[$entityName]);
    }
}
