<?php

namespace App\Repository;

use App\Entity\UserBackendInformation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserBackendInformation>
 *
 * @method UserBackendInformation|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserBackendInformation|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserBackendInformation[]    findAll()
 * @method UserBackendInformation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserBackendInformationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserBackendInformation::class);
    }

//    /**
//     * @return UserBackendInformation[] Returns an array of UserBackendInformation objects
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

//    public function findOneBySomeField($value): ?UserBackendInformation
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
