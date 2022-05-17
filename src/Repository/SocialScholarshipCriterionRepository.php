<?php

namespace App\Repository;

use App\Entity\SocialScholarshipCriterion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SocialScholarshipCriterion|null find($id, $lockMode = null, $lockVersion = null)
 * @method SocialScholarshipCriterion|null findOneBy(array $criteria, array $orderBy = null)
 * @method SocialScholarshipCriterion[]    findAll()
 * @method SocialScholarshipCriterion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SocialScholarshipCriterionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SocialScholarshipCriterion::class);
    }
}
