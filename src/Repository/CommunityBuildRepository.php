<?php

namespace App\Repository;

use App\Entity\CommunityBuild;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommunityBuild|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommunityBuild|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommunityBuild[]    findAll()
 * @method CommunityBuild[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommunityBuildRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommunityBuild::class);
    }

    // /**
    //  * @return CommunityBuild[] Returns an array of CommunityBuild objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CommunityBuild
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
