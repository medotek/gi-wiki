<?php

namespace App\Repository;

use App\Entity\Artifact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Artifact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Artifact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Artifact[]    findAll()
 * @method Artifact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArtifactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Artifact::class);
    }

    // /**
    //  * @return Artifact[] Returns an array of Artifact objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Artifact
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
