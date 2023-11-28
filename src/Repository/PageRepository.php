<?php

namespace App\Repository;

use App\Entity\Page;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Page>
 *
 * @method Page|null find($id, $lockMode = null, $lockVersion = null)
 * @method Page|null findOneBy(array $criteria, array $orderBy = null)
 * @method Page[]    findAll()
 * @method Page[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Page::class);
    }

    public function findAllOrderBy(string $order = 'ASC'): array
    {
        $query = $this->em()->createQuery('SELECT p FROM App\Entity\Page p ORDER BY p.id ' . $order);

        return $query->getResult();
    }

    public function findByCriteria(string $criteria, string $order = 'ASC'): array
    {
        $criteria = trim($criteria);

        $query = $this->em()->createQuery('SELECT p FROM App\Entity\Page p WHERE p.name LIKE :criteria OR p.slug LIKE :criteria OR p.id LIKE :criteria ORDER BY p.id ' . $order);
        $query->setParameter('criteria', '%' . $criteria . '%');

        return $query->getResult();
    }

    public function getChildren(Page $page): array
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT p FROM App\Entity\Page p
            WHERE p.parent = :parent'
        );

        $query->setParameter('parent', $page);

        return $query->getResult();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function getLastDevKey(): ?int
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT MAX(p.devKey) FROM App\Entity\Page p'
        );

        if (null === $query->getSingleScalarResult()) {
            return null;
        }

        return (int) $query->getSingleScalarResult();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function getPageFromDataDevKey(int $dataDevKey): ?Page
    {
        $dataEnum = $this->em()->createQuery(
            'SELECT de FROM App\Entity\DataEnum de
            WHERE de.devKey = :dataDevKey'
        );
        $dataEnum->setParameter('dataDevKey', $dataDevKey);

        $result = $dataEnum->getResult();

        if (0 === \count($result)) {
            return null;
        }
        $dataEnumObject = $result[0];
        $dataEnumValue = $dataEnumObject->getValue();

        $page = $this->em()->createQuery('SELECT p
                FROM App\Entity\Page p
                WHERE p.devKey = :dataEnumValue
            ');
        $page->setParameter('dataEnumValue', $dataEnumValue);

        $page = $page->getResult();


        if (0 === \count($page)) {
            return null;
        }

        return $page[0];
    }

    /**
     * @throws NonUniqueResultException
     */
    public function getPageBySlug(string $slug): ?Page
    {
        $query = $this->em()->createQuery(
            'SELECT p FROM App\Entity\Page p
            WHERE p.slug = :slug'
        );

        $query->setParameter('slug', $slug);

        if (null === $query->getOneOrNullResult()) {
            return null;
        }

        return $query->getOneOrNullResult();
    }

    // Base

    private function em(): EntityManagerInterface
    {
        return $this->_em;
    }
}