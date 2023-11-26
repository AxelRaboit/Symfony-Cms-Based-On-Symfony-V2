<?php

namespace App\EventSubscriber;

use App\Entity\UserApplication;
use App\Entity\UserBackend;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityManagerInterface;

class LastLoginSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager){}

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