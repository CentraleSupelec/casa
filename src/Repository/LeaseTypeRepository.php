<?php

namespace App\Repository;

use App\Entity\LeaseType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LeaseType>
 *
 * @method LeaseType|null find($id, $lockMode = null, $lockVersion = null)
 * @method LeaseType|null findOneBy(array $criteria, array $orderBy = null)
 * @method LeaseType[]    findAll()
 * @method LeaseType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LeaseTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LeaseType::class);
    }

    public function add(LeaseType $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(LeaseType $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
