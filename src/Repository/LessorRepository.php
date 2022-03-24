<?php

namespace App\Repository;

use App\Entity\Lessor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Lessor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lessor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lessor[]    findAll()
 * @method Lessor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LessorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lessor::class);
    }
}
