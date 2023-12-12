<?php

namespace App\Repository;

use App\Entity\Menu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Menu>
 *
 * @method Menu|null find($id, $lockMode = null, $lockVersion = null)
 * @method Menu|null findOneBy(array $criteria, array $orderBy = null)
 * @method Menu[]    findAll()
 * @method Menu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Menu::class);
    }

    public function findAllOrderBy(string $order = 'ASC'): array
    {
        $query = $this->em()->createQuery('SELECT pt FROM App\Entity\Menu pt ORDER BY pt.id ' . $order);

        return $query->getResult();
    }

    public function findByCriteria(string $criteria, string $order = 'ASC'): array
    {
        $criteria = trim($criteria);

        $query = $this->em()->createQuery('SELECT m FROM App\Entity\Menu m WHERE m.name LIKE :criteria OR m.id LIKE :criteria ORDER BY m.id ' . $order);
        $query->setParameter('criteria', '%' . $criteria . '%');

        return $query->getResult();
    }

    private function em(): EntityManagerInterface
    {
        return $this->_em;
    }
}
