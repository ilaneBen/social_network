<?php

namespace App\Repository;

use App\Entity\Follow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Follow>
 */
class FollowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Follow::class);
    }
   /**
    * @param int $userId L'ID de l'utilisateur dont on veut récupérer les followers.
    * @return array La liste des followers de l'utilisateur.
    */
    public function findFollowersByUserId(int $userId): array
    {
        return $this->createQueryBuilder('f')
            ->where('f.following = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }

    public function findFollowingByUserId(int $userId): array
    {
        return $this->createQueryBuilder('f')
            ->where('f.follower = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }

   public function isUserFollowing(int $followerId, int $followingId): bool
   {
       $follow = $this->createQueryBuilder('f')
           ->where('f.follower = :followerId')
           ->andWhere('f.following = :followingId')
           ->setParameter('followerId', $followerId)
           ->setParameter('followingId', $followingId)
           ->getQuery()
           ->getOneOrNullResult();

       return $follow !== null;
   }
    //    /**
    //     * @return Follow[] Returns an array of Follow objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Follow
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
