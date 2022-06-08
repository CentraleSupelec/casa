<?php

namespace App\Repository;

use App\Entity\EmergencyQualificationQuestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EmergencyQualificationQuestion>
 *
 * @method EmergencyQualificationQuestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmergencyQualificationQuestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmergencyQualificationQuestion[]    findAll()
 * @method EmergencyQualificationQuestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmergencyQualificationQuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmergencyQualificationQuestion::class);
    }
}
