<?php

namespace App\Repository;

use App\Entity\DataEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DataEnum>
 *
 * @method DataEnum|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataEnum|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataEnum[]    findAll()
 * @method DataEnum[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataEnumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataEnum::class);
    }

//    /**
//     * @return DataEnum[] Returns an array of DataEnum objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DataEnum
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
