<?php

namespace App\Repository;

use App\Entity\School;
use App\Entity\SchoolEmergencyQualificationQuestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SchoolEmergencyQualificationQuestion>
 *
 * @method SchoolEmergencyQualificationQuestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method SchoolEmergencyQualificationQuestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method SchoolEmergencyQualificationQuestion[]    findAll()
 * @method SchoolEmergencyQualificationQuestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SchoolEmergencyQualificationQuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SchoolEmergencyQualificationQuestion::class);
    }

    public function getSchoolEmergencyQualificationQuestions(School $school, array $questions): QueryBuilder
    {
        return $this
            ->createQueryBuilder('school_emergency_qualification_question')
            ->innerJoin(
                'school_emergency_qualification_question.school',
                'school',
                Join::WITH,
                'school = :school'
            )
            ->setParameter('school', $school)
            ->andWhere(
                'school_emergency_qualification_question.question IN (:questions)'
            )
            ->setParameter('questions', $questions);
    }
}
