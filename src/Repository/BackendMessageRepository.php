<?php

namespace App\Repository;

use App\Entity\BackendMessage;
use App\Entity\UserBackend;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BackendMessage>
 *
 * @method BackendMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method BackendMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method BackendMessage[]    findAll()
 * @method BackendMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BackendMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BackendMessage::class);
    }

    public function findAllMessageReceivedByReceiver(UserBackend $userBackend): array
    {
        $userBackendId = $userBackend->getId();

        return $this->createQueryBuilder('m')
            ->join('m.receiver', 'r')
            ->andWhere('r.id = :receiverId')
            ->andWhere('m.deletedBySenderAt IS NULL')
            ->setParameter('receiverId', $userBackendId)
            ->orderBy('m.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllMessageSentBySender(UserBackend $userBackend): array
    {
        $userBackendId = $userBackend->getId();

        return $this->createQueryBuilder('m')
            ->join('m.sender', 's')
            ->andWhere('s.id = :senderId')
            ->andWhere('m.deletedByReceiverAt IS NULL')
            ->setParameter('senderId', $userBackendId)
            ->orderBy('m.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllMessageDeletedBySenderAndReceiver(): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.deletedBySenderAt IS NOT NULL')
            ->andWhere('m.deletedByReceiverAt IS NOT NULL')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function messageDeletedBySenderAndReceiverCount(): int
    {
        return $this->createQueryBuilder('m')
            ->select('count(m.id)')
            ->andWhere('m.deletedBySenderAt IS NOT NULL')
            ->andWhere('m.deletedByReceiverAt IS NOT NULL')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
