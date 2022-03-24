<?php

namespace App\Repository;

use App\Entity\HousingPicture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HousingPicture|null find($id, $lockMode = null, $lockVersion = null)
 * @method HousingPicture|null findOneBy(array $criteria, array $orderBy = null)
 * @method HousingPicture[]    findAll()
 * @method HousingPicture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HousingPictureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HousingPicture::class);
    }
}
