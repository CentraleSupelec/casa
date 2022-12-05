<?php

namespace App\Repository;

use App\Entity\LessorAdminUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LessorAdminUser>
 *
 * @method LessorAdminUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method LessorAdminUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method LessorAdminUser[]    findAll()
 * @method LessorAdminUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LessorAdminUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LessorAdminUser::class);
    }

    public function add(LessorAdminUser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(LessorAdminUser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
