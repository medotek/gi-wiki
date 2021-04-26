<?php

namespace App\Repository;

use App\Entity\UidReloadDate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UidReloadDate|null find($id, $lockMode = null, $lockVersion = null)
 * @method UidReloadDate|null findOneBy(array $criteria, array $orderBy = null)
 * @method UidReloadDate[]    findAll()
 * @method UidReloadDate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UidReloadDateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UidReloadDate::class);
    }

    // /**
    //  * @return UidReloadDate[] Returns an array of UidReloadDate objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UidReloadDate
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
