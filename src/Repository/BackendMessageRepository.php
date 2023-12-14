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

    /**
     * @param UserBackend $userBackend
     * @return BackendMessage[]
     */
    public function findAllMessageReceivedByReceiver(UserBackend $userBackend): array
    {
        $userBackendId = $userBackend->getId();

        /** @var BackendMessage[] $result */
        $result = $this->createQueryBuilder('m')
            ->join('m.receiver', 'r')
            ->andWhere('r.id = :receiverId')
            ->andWhere('m.deletedByReceiverAt IS NULL')
            ->setParameter('receiverId', $userBackendId)
            ->orderBy('m.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $result;
    }

    /**
     * @param UserBackend $userBackend
     * @return BackendMessage[]
     */
    public function findAllMessageSentBySender(UserBackend $userBackend): array
    {
        $userBackendId = $userBackend->getId();

        /** @var BackendMessage[] $result */
        $result = $this->createQueryBuilder('m')
            ->join('m.sender', 's')
            ->andWhere('s.id = :senderId')
            ->andWhere('m.deletedBySenderAt IS NULL')
            ->setParameter('senderId', $userBackendId)
            ->orderBy('m.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $result;
    }

    /**
     * @return BackendMessage[]
     */
    public function findAllMessageDeletedBySenderAndReceiver(): array
    {
        /** @var BackendMessage[] $result */
        $result = $this->createQueryBuilder('m')
            ->andWhere('m.deletedBySenderAt IS NOT NULL')
            ->andWhere('m.deletedByReceiverAt IS NOT NULL')
            ->getQuery()
            ->getResult();

        return $result;
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function messageDeletedBySenderAndReceiverCount(): int
    {
        $result = $this->createQueryBuilder('m')
            ->select('count(m.id)')
            ->andWhere('m.deletedBySenderAt IS NOT NULL')
            ->andWhere('m.deletedByReceiverAt IS NOT NULL')
            ->getQuery()
            ->getSingleScalarResult();

        return (int) $result;
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function findCountMessageNotReadByReceiver(UserBackend $userBackend): int
    {
        /** @var int $userBackendId */
        $userBackendId = $userBackend->getId();

        $result = $this->createQueryBuilder('m')
            ->select('count(m.id)')
            ->join('m.receiver', 'r')
            ->andWhere('r.id = :receiverId')
            ->andWhere('m.isRead = false')
            ->andWhere('m.deletedByReceiverAt IS NULL')
            ->setParameter('receiverId', $userBackendId)
            ->getQuery()
            ->getSingleScalarResult();

        return (int) $result;
    }
}
