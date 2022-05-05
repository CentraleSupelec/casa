<?php

namespace App\DataFixtures;

use App\DataFixtures\Factory\LoaderFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->LoadEntities($manager, 'src/DataFixtures/data/ServiceData.yaml', 'services');
        $this->LoadEntities($manager, 'src/DataFixtures/data/EquipmentsData.yaml', 'equipments');
        $this->LoadEntities($manager, 'src/DataFixtures/data/LessorData.yaml', 'lessors');
        $this->LoadEntities($manager, 'src/DataFixtures/data/SchoolData.yaml', 'schools');
        $this->LoadEntities($manager, 'src/DataFixtures/data/TwentyCampusData.yaml', 'housingGroups');
        $this->LoadEntities($manager, 'src/DataFixtures/data/ArpejData.yaml', 'housingGroups');
    }

    public function LoadEntities(ObjectManager $manager, string $fileName, $entityName)
    {
        $entities = Yaml::parseFile($fileName);
        LoaderFactory::getLoader($manager, $entityName, $entities)->load();
    }
}
