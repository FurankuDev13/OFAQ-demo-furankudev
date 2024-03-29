<?php

namespace App\Repository;

use App\Entity\VoteForQuestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method VoteForQuestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method VoteForQuestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method VoteForQuestion[]    findAll()
 * @method VoteForQuestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoteForQuestionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, VoteForQuestion::class);
    }

    public function findAllByValue($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.value = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    
    // /**
    //  * @return VoteForQuestion[] Returns an array of VoteForQuestion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VoteForQuestion
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
