<?php

namespace App\Repository;

use App\Entity\HousingGroup;
use App\Entity\Lessor;
use App\Model\SearchHousingGroupCriteriaModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
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
            ->select('UPPER(h.address.city) as city')
            ->distinct()
            ->orderBy('city', 'ASC')
            ->getQuery()
            ->getResult();

        return array_column($results, 'city');
    }

    public function getLessorDistinctCities(Lessor $lessor): array
    {
        $results = $this->createQueryBuilder('h')
            ->select('UPPER(h.address.city) as city')
            ->distinct()
            ->innerJoin('h.lessor', 'l', Join::WITH, 'l.id = :lessorId')
            ->setParameter('lessorId', $lessor->getId())
            ->orderBy('city', 'ASC')
            ->getQuery()
            ->getResult();

        return array_column($results, 'city');
    }

    public function getLessorHousingGroupListQueryBuilder(Lessor $lessor, SearchHousingGroupCriteriaModel $searchHousingGroupCriteriaModel): QueryBuilder
    {
        $queryBuilder = $this
            ->createQueryBuilder('h')
                ->innerJoin('h.lessor', 'l', Join::WITH, 'l.id = :lessorId')
                ->setParameter('lessorId', $lessor->getId());

        if ($searchHousingGroupCriteriaModel->getCity()) {
            $queryBuilder
                ->andWhere('UPPER(h.address.city) = :city')
                ->setParameter('city', $searchHousingGroupCriteriaModel->getCity());
        }

        $queryBuilder = $queryBuilder->orderBy('h.name');

        return $queryBuilder;
    }
}
