<?php

namespace App\Repository;

use App\Entity\PageType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PageType>
 *
 * @method PageType|null find($id, $lockMode = null, $lockVersion = null)
 * @method PageType|null findOneBy(array $criteria, array $orderBy = null)
 * @method PageType[]    findAll()
 * @method PageType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PageType::class);
    }

    public function findAllOrderBy(string $order = 'ASC'): array
    {
        $query = $this->em()->createQuery('SELECT pt FROM App\Entity\PageType pt ORDER BY pt.id ' . $order);

        return $query->getResult();
    }

    public function findByCriteria(string $criteria, string $order = 'ASC'): array
    {
        $criteria = trim($criteria);

        $query = $this->em()->createQuery('SELECT pt FROM App\Entity\PageType pt WHERE pt.name LIKE :criteria OR pt.id LIKE :criteria ORDER BY pt.id ' . $order);
        $query->setParameter('criteria', '%' . $criteria . '%');

        return $query->getResult();
    }

    // Base

    private function em(): EntityManagerInterface
    {
        return $this->_em;
    }
}
