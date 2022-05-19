<?php

namespace App\Repository;

use App\Entity\HousingGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HousingGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method HousingGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method HousingGroup[]    findAll()
 * @method HousingGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HousingGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HousingGroup::class);
    }

    public function getDistinctCities(): array
    {
        $results = $this->createQueryBuilder('h')
            ->select('h.address.city')
            ->distinct()
            ->getQuery()
            ->getResult();

        return array_column($results, 'address.city');
    }
}
