<?php

namespace App\Repository;

use App\Entity\BackendMessage;
use App\Entity\UserBackend;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
            ->setParameter('senderId', $userBackendId)
            ->orderBy('m.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}
