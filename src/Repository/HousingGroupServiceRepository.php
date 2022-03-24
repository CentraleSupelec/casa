<?php

namespace App\Repository;

use App\Entity\HousingGroupService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HousingGroupService|null find($id, $lockMode = null, $lockVersion = null)
 * @method HousingGroupService|null findOneBy(array $criteria, array $orderBy = null)
 * @method HousingGroupService[]    findAll()
 * @method HousingGroupService[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HousingGroupServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HousingGroupService::class);
    }
}
