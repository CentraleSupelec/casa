<?php

namespace App\Repository;

use App\Entity\Housing;
use App\Model\SearchCriteriaModel;
use App\Model\StudentProfileCriteriaModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
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

    public function getHousingListQueryBuilder(
        SearchCriteriaModel $searchCriteria,
        StudentProfileCriteriaModel $studentProfileCriteria = new StudentProfileCriteriaModel()
    ): QueryBuilder {
        $queryBuilder = $this
            ->createQueryBuilder('h')
            ->addSelect(
                'CASE WHEN :now between sc.startDate and sc.endDate or :now between ssc.startDate and ssc.endDate THEN true ELSE false END as hasCriteria'
            )
            ->setParameter('now', (new \DateTime())->format('Y-m-d'));

        $queryBuilder = $this
            ->addSelectIsPriority($queryBuilder, $studentProfileCriteria)
            ->leftJoin('h.socialScholarshipCriteria', 'ssc')
            ->leftJoin('h.schoolCriteria', 'sc');

        return $this
            ->applySearchCriteriaFilter($queryBuilder, $searchCriteria)
            ->addOrderBy('isPriority', 'desc')
            ->addOrderBy('h.createdAt', 'asc');
    }

    private function addSelectIsPriority(
        QueryBuilder $queryBuilder, StudentProfileCriteriaModel $studentProfileCriteriaModel
    ): QueryBuilder {
        $selectIsPriority = 'h.id is null';     // condition that is always false
        $selectSocialScholarshipCriteria = ':now between ssc.startDate and ssc.endDate';
        $selectSchoolCriteria =
            'sc.id is null or (:now between sc.startDate and sc.endDate and :school MEMBER OF sc.schools)';
        $parameters = [];

        if ($studentProfileCriteriaModel->getSocialScholarship()) {
            $selectIsPriority = $selectSocialScholarshipCriteria;
            $parameters['now'] = (new \DateTime())->format('Y-m-d');

            if (null !== $studentProfileCriteriaModel->getSchool()) {
                $selectIsPriority = $selectIsPriority.' and '.$selectSchoolCriteria;
                $parameters['school'] = $studentProfileCriteriaModel->getSchool();
            }
        } elseif (null !== $studentProfileCriteriaModel->getSchool()) {
            $selectIsPriority = $selectSchoolCriteria;
            $parameters['school'] = $studentProfileCriteriaModel->getSchool();
        }

        $queryBuilder->addSelect('CASE WHEN '.$selectIsPriority.' THEN true ELSE false END as isPriority');

        if (sizeof($parameters) > 0) {
            $queryBuilder->setParameters($parameters);
        }

        return $queryBuilder;
    }

    private function applySearchCriteriaFilter(
        QueryBuilder $queryBuilder, SearchCriteriaModel $searchCriteriaModel
    ): QueryBuilder {
        if ($searchCriteriaModel->getCity()) {
            $queryBuilder
                ->innerJoin('h.housingGroup', 'hg', Join::WITH, 'hg.address.city = :city')
                ->setParameter('city', $searchCriteriaModel->getCity());
        }

        if ($searchCriteriaModel->getMaxPrice()) {
            $queryBuilder
                ->andWhere('h.rentMin <= :price ')
                ->setParameter('price', $searchCriteriaModel->getMaxPrice());
        }
        if ($searchCriteriaModel->getMinArea()) {
            $queryBuilder
                ->andWhere('h.areaMin >= :area or h.areaMax>=:area')
                ->setParameter('area', $searchCriteriaModel->getMinArea());
        }
        if ($searchCriteriaModel->getAccessibility()) {
            $queryBuilder->andWhere('h.accessibility = true');
        }

        return $queryBuilder;
    }
}
