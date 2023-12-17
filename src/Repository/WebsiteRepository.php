<?php

namespace App\Repository;

use App\Entity\Website;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Website>
 *
 * @method Website|null find($id, $lockMode = null, $lockVersion = null)
 * @method Website|null findOneBy(array $criteria, array $orderBy = null)
 * @method Website[]    findAll()
 * @method Website[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebsiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Website::class);
    }

    /**
     * @return Website[]
     */
    public function findByCriteria(string $criteria, string $order = 'ASC'): array
    {
        $criteria = trim($criteria);

        $query = $this->em()->createQuery('SELECT w FROM App\Entity\Website w WHERE w.name LIKE :criteria OR w.id LIKE :criteria ORDER BY w.id '.$order);
        $query->setParameter('criteria', '%'.$criteria.'%');

        /** @var Website[] $result */
        $result = $query->getResult();

        return $result;
    }

    /**
     * @return Website[]
     */
    public function findAllOrderBy(string $order = 'ASC'): array
    {
        $query = $this->em()->createQuery('SELECT w FROM App\Entity\Website w ORDER BY w.id '.$order);

        /** @var Website[] $result */
        $result = $query->getResult();

        return $result;
    }

    // base

    private function em(): EntityManagerInterface
    {
        return $this->_em;
    }
}
