<?php

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;

class AbstractManager
{
    public function __construct(private readonly EntityManagerInterface $em){}

    protected function save(object $entity): void
    {
        if (method_exists($entity, 'getCreatedAt')) {
            if($entity->getCreatedAt() === null) {
                $entity->setCreatedAt(new \DateTimeImmutable());
            }
        }

        if (method_exists($entity, 'getUpdatedAt')) {
            $entity->setUpdatedAt(new \DateTimeImmutable());
        }

        $this->em->persist($entity);
        $this->em->flush();
    }

    protected function remove(object $entity): void
    {
        $this->em->remove($entity);
        $this->em->flush();
    }
}