<?php

namespace App\Repository;

use App\Entity\SchoolCriterion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SchoolCriterion|null find($id, $lockMode = null, $lockVersion = null)
 * @method SchoolCriterion|null findOneBy(array $criteria, array $orderBy = null)
 * @method SchoolCriterion[]    findAll()
 * @method SchoolCriterion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SchoolCriterionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SchoolCriterion::class);
    }
}
