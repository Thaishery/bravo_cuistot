<?php

namespace App\Repository;

use App\Entity\Etapes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Etapes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Etapes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Etapes[]    findAll()
 * @method Etapes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtapesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Etapes::class);
    }

    // /**
    //  * @return Etapes[] Returns an array of Etapes objects
    //  */
    
    public function findByRecetteId($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.recette_id = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?Etapes
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
