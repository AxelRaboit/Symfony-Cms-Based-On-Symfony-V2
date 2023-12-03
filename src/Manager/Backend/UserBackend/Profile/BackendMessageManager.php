<?php

namespace App\Manager\Backend\UserBackend\Profile;

use App\Entity\BackendMessage;
use App\Manager\AbstractManager;
use App\Repository\BackendMessageRepository;
use App\Repository\UserBackendRepository;
use Doctrine\ORM\EntityManagerInterface;

class BackendMessageManager extends AbstractManager
{
    public function __construct(
        EntityManagerInterface $em,
        private readonly UserBackendRepository $userBackendRepository,
        private readonly BackendMessageRepository $backendMessageRepository
    )
    {
        parent::__construct($em);
    }

    /**
     * @throws \Exception
     */
    public function messageCreate(BackendMessage $backendMessage, string $sender): void
    {
        $userBackend = $this->findUserBackendByEmail($sender);
        $backendMessage->setSender($userBackend);
        $this->save($backendMessage);
    }

    public function findUserBackendByEmail(string $email): object
    {
        return $this->userBackendRepository->findOneBy(['email' => $email]);
    }

    public function messageDeleteFromSender(BackendMessage $backendMessage): void
    {
        $this->softRemoveBySender($backendMessage);
    }

    public function messageDeleteFromReceiver(BackendMessage $backendMessage): void
    {
        $this->softRemoveByReceiver($backendMessage);
    }

    public function deleteMessagesDeletedBySenderAndReceiver(): void
    {
        $messages = $this->backendMessageRepository->findAllMessageDeletedBySenderAndReceiver();

        foreach ($messages as $message) {
            $this->remove($message);
        }
    }
}