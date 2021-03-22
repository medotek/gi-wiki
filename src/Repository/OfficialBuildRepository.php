<?php

namespace App\Repository;

use App\Entity\OfficialBuild;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OfficialBuild|null find($id, $lockMode = null, $lockVersion = null)
 * @method OfficialBuild|null findOneBy(array $criteria, array $orderBy = null)
 * @method OfficialBuild[]    findAll()
 * @method OfficialBuild[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OfficialBuildRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OfficialBuild::class);
    }

    // /**
    //  * @return OfficialBuild[] Returns an array of OfficialBuild objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OfficialBuild
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
