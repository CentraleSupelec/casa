<?php

namespace App\Repository;

use App\Entity\StayDuration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StayDuration>
 *
 * @method StayDuration|null find($id, $lockMode = null, $lockVersion = null)
 * @method StayDuration|null findOneBy(array $criteria, array $orderBy = null)
 * @method StayDuration[]    findAll()
 * @method StayDuration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StayDurationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StayDuration::class);
    }

    public function add(StayDuration $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(StayDuration $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
