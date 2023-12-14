<?php

namespace App\Repository;

use App\Entity\PageType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
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

    /**
     * @param string $order
     * @return mixed
     */
    public function findAllOrderBy(string $order = 'ASC'): mixed
    {
        $query = $this->em()->createQuery('SELECT pt FROM App\Entity\PageType pt ORDER BY pt.id ' . $order);

        return $query->getResult();
    }

    /**
     * @param string $criteria
     * @param string $order
     * @return mixed
     */
    public function findByCriteria(string $criteria, string $order = 'ASC'): mixed
    {
        $criteria = trim($criteria);

        $query = $this->em()->createQuery('SELECT pt FROM App\Entity\PageType pt WHERE pt.name LIKE :criteria OR pt.id LIKE :criteria ORDER BY pt.id ' . $order);
        $query->setParameter('criteria', '%' . $criteria . '%');

        return $query->getResult();
    }

    /**
     * We take the highest devKey and increment it by 1 to get the next available devKey.
     * We make sure that the devKey is unique.
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function createDevKey(): int
    {
        $query = $this->em()->createQuery('SELECT MAX(pt.devKey) FROM App\Entity\PageType pt');

        $devKey = $query->getSingleScalarResult();

        while ($this->findOneBy(['devKey' => $devKey])) {
            $devKey++;
        }

        return (int) $devKey;
    }

    // Base

    private function em(): EntityManagerInterface
    {
        return $this->_em;
    }
}
