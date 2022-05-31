<?php

namespace App\Repository;

use App\Entity\Housing;
use App\Entity\School;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method School|null find($id, $lockMode = null, $lockVersion = null)
 * @method School|null findOneBy(array $criteria, array $orderBy = null)
 * @method School[]    findAll()
 * @method School[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SchoolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, School::class);
    }

    public function getHousingSchoolsWithCriteria(Housing $housing): QueryBuilder
    {
        return $this
            ->createQueryBuilder('school')
            ->innerJoin(
                'school.schoolCriteria',
                'schoolCriteria',
                Join::WITH,
                'schoolCriteria.housing = :housing and :now between schoolCriteria.startDate and schoolCriteria.endDate'
            )
            ->setParameters([
                'housing' => $housing,
                'now' => (new \DateTime())->format('Y-m-d'),
            ])
            ->distinct();
    }
}
