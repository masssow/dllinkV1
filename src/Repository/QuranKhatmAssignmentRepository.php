<?php

namespace App\Repository;

use App\Entity\QuranKhatmAssignment;
use App\Entity\QuranSession;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuranKhatmAssignment>
 */
class QuranKhatmAssignmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuranKhatmAssignment::class);
    }


    public function countCompleted(QuranSession $session)
    {
        return $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->where('a.quranSession = :session')
            ->andWhere('a.isCompleted = true')
            ->setParameter('session', $session)
            ->getQuery()
            ->getSingleScalarResult();
    }
    
    //    /**
    //     * @return QuranKhatmAssignment[] Returns an array of QuranKhatmAssignment objects
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

    //    public function findOneBySomeField($value): ?QuranKhatmAssignment
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
