<?php

namespace App\Repository;

use App\Entity\DeclarationTrouve;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DeclarationTrouve|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeclarationTrouve|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeclarationTrouve[]    findAll()
 * @method DeclarationTrouve[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeclarationTrouveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeclarationTrouve::class);
    }

    // /**
    //  * @return DeclarationTrouve[] Returns an array of DeclarationTrouve objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DeclarationTrouve
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
