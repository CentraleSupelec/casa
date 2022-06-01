<?php

namespace App\Repository;

use App\Entity\ParentSchool;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ParentSchool|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParentSchool|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParentSchool[]    findAll()
 * @method ParentSchool[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParentSchoolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParentSchool::class);
    }
}
