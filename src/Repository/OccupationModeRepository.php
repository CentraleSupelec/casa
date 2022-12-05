<?php

namespace App\Repository;

use App\Entity\OccupationMode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OccupationMode>
 *
 * @method OccupationMode|null find($id, $lockMode = null, $lockVersion = null)
 * @method OccupationMode|null findOneBy(array $criteria, array $orderBy = null)
 * @method OccupationMode[]    findAll()
 * @method OccupationMode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OccupationModeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OccupationMode::class);
    }

    public function add(OccupationMode $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(OccupationMode $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
