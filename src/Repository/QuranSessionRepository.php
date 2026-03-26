<?php

namespace App\Repository;

use App\Entity\QuranSession;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuranSession>
 */
class QuranSessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuranSession::class);
    }


    public function findByOwner(User $user)
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.quranKhatmAssignments', 'a')
            ->addSelect('a')
            ->where('s.owner = :user')
            ->setParameter('user', $user)
            ->orderBy('s.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByOwnerWithAssignments(User $user)
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.quranKhatmAssignments', 'a')
            ->addSelect('a')
            ->where('s.owner = :user')
            ->setParameter('user', $user)
            ->orderBy('s.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return QuranSession[] Returns an array of QuranSession objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?QuranSession
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
