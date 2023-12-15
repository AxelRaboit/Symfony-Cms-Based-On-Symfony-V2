<?php

namespace App\EventSubscriber;

use App\Entity\UserApplication;
use App\Entity\UserBackend;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

readonly class LastLoginSubscriber implements EventSubscriberInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            InteractiveLoginEvent::class => 'onInteractiveLogin',
        ];
    }

    public function onInteractiveLogin(InteractiveLoginEvent $event): void
    {
        $user = $event->getAuthenticationToken()->getUser();

        if ($user instanceof UserBackend || $user instanceof UserApplication) {
            $user->setLastLoginAt(new \DateTimeImmutable());
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }
}
