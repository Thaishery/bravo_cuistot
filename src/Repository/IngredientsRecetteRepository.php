<?php

namespace App\Repository;

use App\Entity\IngredientsRecette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method IngredientsRecette|null find($id, $lockMode = null, $lockVersion = null)
 * @method IngredientsRecette|null findOneBy(array $criteria, array $orderBy = null)
 * @method IngredientsRecette[]    findAll()
 * @method IngredientsRecette[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IngredientsRecetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IngredientsRecette::class);
    }

    // /**
    //  * @return IngredientsRecette[] Returns an array of IngredientsRecette objects
    //  */
    
    public function findByRecetteId($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.recette_id = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

     public function findByIngredients($value) {
        return $this->createQueryBuilder('i')
            ->andWhere('i.ingredients_id = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->getQuery()
            ->getResult()
         ;
     }


   

    /*
    public function findOneBySomeField($value): ?IngredientsRecette
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

}
