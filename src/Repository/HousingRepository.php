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

    public function getHousingBookmarksQueryBuilder(
        StudentProfileCriteriaModel $studentProfileCriteria, string $studentId
    ): QueryBuilder {
        return $this
            ->getHousingBaseQueryBuilderWithCriteriaAndPriority(new SearchCriteriaModel(), $studentProfileCriteria)
            ->innerJoin('h.students', 's', Join::WITH, 's.id = :studentId')
            ->setParameter('studentId', $studentId)
            ->addOrderBy('isPriority', 'desc')
            ->addOrderBy('h.createdAt', 'asc');
    }

    public function getHousingListQueryBuilder(
        SearchCriteriaModel $searchCriteria,
        StudentProfileCriteriaModel $studentProfileCriteria = new StudentProfileCriteriaModel(null)
    ): QueryBuilder {
        return $this
            ->getHousingBaseQueryBuilderWithCriteriaAndPriority($searchCriteria, $studentProfileCriteria)
            ->addOrderBy('isPriority', 'desc')
            ->addOrderBy('h.createdAt', 'asc');
    }

    public function getHousingByIdQueryBuilder(
        StudentProfileCriteriaModel $studentProfileCriteria, string $housingId
    ): QueryBuilder {
        $now = (new \DateTime())->format('Y-m-d');

        return $this
            ->getHousingBaseQueryBuilderWithCriteriaAndPriority(new SearchCriteriaModel(), $studentProfileCriteria)
            ->addSelect(
                'CASE WHEN :now between ssc.startDate and ssc.endDate THEN true ELSE false END as hasSocialScholarshipCriteria'
            )
            ->setParameter('now', $now)
            ->addSelect(
                'CASE WHEN :now between sc.startDate and sc.endDate THEN true ELSE false END as hasSchoolCriteria'
            )
            ->setParameter('now', $now)
            ->andWhere('h.id = :housingId')
            ->setParameter('housingId', $housingId);
    }

    private function getHousingBaseQueryBuilderWithCriteriaAndPriority(
        SearchCriteriaModel $searchCriteria,
        StudentProfileCriteriaModel $studentProfileCriteria
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

        return $this->applySearchCriteriaFilter($queryBuilder, $searchCriteria);
    }

    private function addSelectIsPriority(
        QueryBuilder $queryBuilder, StudentProfileCriteriaModel $studentProfileCriteriaModel
    ): QueryBuilder {
        $now = (new \DateTime())->format('Y-m-d');
        $parameters = [];
        $hasSocialScholarshipCriterion = $queryBuilder
            ->expr()
            ->between(':now', 'ssc.startDate', 'ssc.endDate');
        $hasSchoolCriterion = $queryBuilder->expr()->andX(
            $queryBuilder->expr()->between(':now', 'sc.startDate', 'sc.endDate'),
            $queryBuilder->expr()->isMemberOf(':school', 'sc.schools')
        );

        if ($studentProfileCriteriaModel->getSocialScholarship()) {
            $parameters['now'] = $now;
            if (null === $studentProfileCriteriaModel->getSchool()) {
                /* Student has social scholarship and no school
                 *      => priority on housing with social scholarship and no school
                 * */
                $selectIsPriority = $queryBuilder
                    ->expr()
                    ->andX($hasSocialScholarshipCriterion, $queryBuilder->expr()->isNull('sc.id'));
            } else {
                /* Student has social scholarship and school
                 *      => priority on housing with { social scholarship and no school } or school including his
                 * */
                $parameters['school'] = $studentProfileCriteriaModel->getSchool();
                $selectIsPriority = $queryBuilder
                    ->expr()
                    ->orX(
                        $queryBuilder
                            ->expr()
                            ->andX($hasSocialScholarshipCriterion, $queryBuilder->expr()->isNull('sc.id')),
                        $hasSchoolCriterion
                    );
            }
        } else {
            if (null !== $studentProfileCriteriaModel->getSchool()) {
                /* Student has no social scholarship and school
                 *      => priority on housing with no social scholarship and school including his
                 * */
                $parameters['now'] = $now;
                $parameters['school'] = $studentProfileCriteriaModel->getSchool();
                $selectIsPriority = $queryBuilder
                    ->expr()
                    ->andX($queryBuilder->expr()->isNull('ssc.id'), $hasSchoolCriterion);
            } else {
                // Student has no social scholarship and no school => no housing should have priority
                $selectIsPriority = $queryBuilder->expr()->isNull('h.id');
            }
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
