<?php

namespace App\Repository;

use App\Entity\MessageTopic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MessageTopic|null find($id, $lockMode = null, $lockVersion = null)
 * @method MessageTopic|null findOneBy(array $criteria, array $orderBy = null)
 * @method MessageTopic[]    findAll()
 * @method MessageTopic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageTopicRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MessageTopic::class);
    }

    // /**
    //  * @return MessageTopic[] Returns an array of MessageTopic objects
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
    public function findOneBySomeField($value): ?MessageTopic
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
