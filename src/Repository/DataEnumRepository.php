<?php

namespace App\Repository;

use App\Entity\DataEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DataEnum>
 *
 * @method DataEnum|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataEnum|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataEnum[]    findAll()
 * @method DataEnum[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataEnumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataEnum::class);
    }

    /**
     * @return DataEnum[]
     */
    public function findByCriteria(string $criteria, string $order = 'ASC'): array
    {
        $criteria = trim($criteria);

        $query = $this->em()->createQuery('SELECT d FROM App\Entity\DataEnum d WHERE d.name LIKE :criteria OR d.value LIKE :criteria OR d.id LIKE :criteria OR d.category LIKE :criteria ORDER BY d.id '.$order);
        $query->setParameter('criteria', '%'.$criteria.'%');

        /** @var DataEnum[] $result */
        $result = $query->getResult();

        return $result;
    }

    /**
     * @return DataEnum[]
     */
    public function findAllOrderBy(string $order = 'ASC'): array
    {
        $query = $this->em()->createQuery('SELECT d FROM App\Entity\DataEnum d ORDER BY d.id '.$order);

        /** @var DataEnum[] $result */
        $result = $query->getResult();

        return $result;
    }

    /**
     * We take the highest devKey and increment it by 1 to get the next available devKey.
     * We make sure that the devKey is unique.
     *
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function createDevKey(): int
    {
        $query = $this->em()->createQuery('SELECT MAX(d.devKey) FROM App\Entity\DataEnum d');

        $devKey = $query->getSingleScalarResult();

        while ($this->findOneBy(['devKey' => $devKey])) {
            ++$devKey;
        }

        return (int) $devKey;
    }

    // base

    private function em(): EntityManagerInterface
    {
        return $this->_em;
    }
}
