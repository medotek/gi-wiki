<?php

namespace App\Repository;

use App\Entity\UserUidCharacter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserUidCharacter|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserUidCharacter|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserUidCharacter[]    findAll()
 * @method UserUidCharacter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserUidCharacterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserUidCharacter::class);
    }

    // /**
    //  * @return UserUidCharacter[] Returns an array of UserUidCharacter objects
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
    public function findOneBySomeField($value): ?UserUidCharacter
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
