<?php

namespace App\Repository;

use App\Entity\MenuItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Collection;

/**
 * @extends ServiceEntityRepository<MenuItem>
 *
 * @method MenuItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method MenuItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method MenuItem[]    findAll()
 * @method MenuItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MenuItem::class);
    }

    public function getMenuItemsSortedByWeight(
        string $menu,
    ): array {
        $query = $this->em()->createQuery('SELECT mi
            FROM App\Entity\MenuItem mi
            JOIN mi.menu m
            WHERE m.name = :menu
        ');

        $query->setParameter('menu', $menu);

        /* return (array) $query->execute(); */
        return $this->sort((array) $query->execute(), true);
    }

    private function sort(
        Collection|array $menuItems,
        bool             $toArray = false
    ): array {
        $data = [];

        foreach ($menuItems as $menuItem) {
            $parentIds = [];
            $menuItemWhile = $menuItem;

            $item = [
                'current' => true === $toArray ? $menuItem->toArray() : $menuItem,
                'children' => [],
            ];

            if (null === $menuItem->getParent()) {
                $data[$menuItem->getId()] = $item;
            } else {
                while (null !== $menuItemWhile->getParent()) {
                    $parentIds[] = $menuItemWhile->getParent()->getId();
                    $menuItemWhile = $menuItemWhile->getParent();
                }

                if (\count($parentIds) > 0) {
                    $pointer = &$data[$parentIds[0]]['children'];
                    for ($i = 1; $i < \count($parentIds); ++$i) {
                        $pointer = &$pointer[$parentIds[$i]]['children'];
                    }
                    $pointer[$menuItem->getId()] = $item;
                }
            }
        }

        usort($data, function ($a, $b) {
            return $a['current']['weight'] <=> $b['current']['weight'];
        });

        return $data;
    }

    // Base

    private function em(): EntityManagerInterface
    {
        return $this->_em;
    }
}
