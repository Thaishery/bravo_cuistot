<?php

namespace App\Repository;

use App\Entity\Cuisson;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cuisson|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cuisson|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cuisson[]    findAll()
 * @method Cuisson[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CuissonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cuisson::class);
    }

    // /**
    //  * @return Cuisson[] Returns an array of Cuisson objects
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
    public function findOneBySomeField($value): ?Cuisson
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
