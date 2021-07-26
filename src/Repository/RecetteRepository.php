<?php

namespace App\Repository;

use App\Entity\Recette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Recette|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recette|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recette[]    findAll()
 * @method Recette[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recette::class);
    }

     // /**
     //  * @return Recette[] Returns an array of Recette objects
     //  */
    
     // ? Recherche par User Id

     public function findByUserId($value) {
         return $this->createQueryBuilder('r')
             ->andWhere('r.author_id = :val')
             ->setParameter('val', $value)
             ->orderBy('r.id', 'ASC')
             // ->setMaxResults(10)
             ->getQuery()
             ->getResult()
         ;
     }

     // ? Recherche par Id

     public function findById($value) {

         return $this->createQueryBuilder('r')
             ->andWhere('r.id = :val')
             ->setParameter('val', $value)
             ->orderBy('r.id', 'ASC')
             // ->setMaxResults(10)
             ->getQuery()
             ->getResult()
         ;
     }
    
     // ? Recherche par niveau de difficulté   

     public function findByRecetteNiveau($value) {

         return $this->createQueryBuilder('r')
             ->andWhere('r.difficulte = :val')
             ->setParameter('val', $value)
             ->orderBy('r.id', 'ASC')
             // ->setMaxResults(10)
             ->getQuery()
             ->getResult()
         ;
     }

     // ? Recherche par type de plat   

     public function findByTypeDePlats($value) {

         return $this->createQueryBuilder('r')
             ->andWhere('r.plats_id = :val')
             ->setParameter('val', $value)
             ->orderBy('r.id', 'ASC')
             // ->setMaxResults(10)
             ->getQuery()
             ->getResult()
         ;
     }

     // ? Recherche par nom  

     public function findByName($value) {

         return $this->createQueryBuilder('r')
             ->andWhere('r.name like :val')
             //  ->setParameter('val', $value)
             ->setParameter('val', '%'.$value.'%')
             ->orderBy('r.id', 'ASC')
             // ->setMaxResults(10)
             ->getQuery()
             ->getResult()
         ;
     }

     // ? Recherche par type d'alimentation  

     public function findByTypeAlimentation($value) {

        return $this->createQueryBuilder('r')
            ->andWhere('r.alimentation_id = :val')
            //  ->setParameter('val', $value)
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            // ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

     
     // ? Recherche par type de cuisson

     public function findByCuisson($value) {

        return $this->createQueryBuilder('r')
            ->andWhere('r.cuisson_id = :val')
            //  ->setParameter('val', $value)
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            // ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

      




    /*
    public function findOneBySomeField($value): ?Recette
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
