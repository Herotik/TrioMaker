<?php

namespace App\Repository;

use App\Entity\ModeDeJeu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ModeDeJeu|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModeDeJeu|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModeDeJeu[]    findAll()
 * @method ModeDeJeu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModeDeJeuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModeDeJeu::class);
    }

    // /**
    //  * @return ModeDeJeu[] Returns an array of ModeDeJeu objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ModeDeJeu
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
