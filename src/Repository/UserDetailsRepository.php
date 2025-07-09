<?php

namespace App\Repository;

use App\Entity\UserDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserDetails>
 */
class UserDetailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserDetails::class);
    }

    /**
     * Search users by name, email, or phone number
     */
    public function searchUsers(string $query, int $limit = 10): array
    {
        $qb = $this->createQueryBuilder('ud')
            ->leftJoin('ud.user', 'u')
            ->where('ud.firstName LIKE :query')
            ->orWhere('ud.lastName LIKE :query')
            ->orWhere('u.email LIKE :query')
            ->orWhere('ud.phoneNo LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->setMaxResults($limit)
            ->orderBy('ud.firstName', 'ASC');

        return $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return UserDetails[] Returns an array of UserDetails objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?UserDetails
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
