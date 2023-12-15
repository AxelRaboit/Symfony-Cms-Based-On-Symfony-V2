<?php

namespace App\Repository;

use App\Entity\IpWhitelist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<IpWhitelist>
 *
 * @method IpWhitelist|null find($id, $lockMode = null, $lockVersion = null)
 * @method IpWhitelist|null findOneBy(array $criteria, array $orderBy = null)
 * @method IpWhitelist[]    findAll()
 * @method IpWhitelist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IpWhitelistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IpWhitelist::class);
    }

    //    /**
    //     * @return IpWhitelist[] Returns an array of IpWhitelist objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?IpWhitelist
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
