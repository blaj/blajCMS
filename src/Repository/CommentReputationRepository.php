<?php

namespace App\Repository;

use App\Entity\CommentReputation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CommentReputation|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentReputation|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentReputation[]    findAll()
 * @method CommentReputation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentReputationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CommentReputation::class);
    }

    // /**
    //  * @return CommentReputation[] Returns an array of CommentReputation objects
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
    public function findOneBySomeField($value): ?CommentReputation
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
