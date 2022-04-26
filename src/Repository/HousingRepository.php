<?php

namespace App\Repository;

use App\Entity\Housing;
use App\Model\SearchCriteriaModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Housing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Housing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Housing[]    findAll()
 * @method Housing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HousingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Housing::class);
    }

    public function qbFindByCriteria(SearchCriteriaModel $searchCriteria): QueryBuilder
    {
        $query = $this->createQueryBuilder('h');

        if ($searchCriteria->getMaxPrice()) {
            $query->where('h.rentMin <= :price ')->setParameter('price', $searchCriteria->getMaxPrice());
        }

        if ($searchCriteria->getMinArea()) {
            $query->andWhere('h.areaMin >=:area')->setParameter('area', $searchCriteria->getMinArea());
        }
        $query->orderBy('h.rentMin', 'ASC');

        return $query;
    }
}
