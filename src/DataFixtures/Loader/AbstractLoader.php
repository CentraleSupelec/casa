<?php

namespace App\DataFixtures\Loader;

use Doctrine\Persistence\ObjectManager;

abstract class AbstractLoader
{
    public function __construct(private ObjectManager $manager, private $entity)
    {
    }

    public function getManager(): ObjectManager
    {
        return $this->manager;
    }

    public function getEntity()
    {
        return $this->entity;
    }

    abstract public function load();
}
