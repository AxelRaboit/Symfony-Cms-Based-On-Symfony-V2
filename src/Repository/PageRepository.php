<?php

namespace App\Repository;

use App\Entity\Page;
use App\Entity\PageType;
use App\Enum\PageStateEnum;
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
        $query = $this->em()->createQuery('SELECT p FROM App\Entity\Page p WHERE p.state != :state ORDER BY p.id ' . $order);
        $query->setParameter('state', PageStateEnum::DELETED);

        return $query->getResult();
    }

    public function findAllByPageTypeOrderBy(PageType $pageType, string $order = 'ASC'): array
    {
        $query = $this->em()->createQuery('SELECT p FROM App\Entity\Page p WHERE p.pageType = :pageType ORDER BY p.id ' . $order);
        $query->setParameter('pageType', $pageType);

        return $query->getResult();
    }

    public function findByCriteria(string $criteria, string $order = 'ASC'): array
    {
        $criteria = trim($criteria);

        $query = $this->em()->createQuery('SELECT p FROM App\Entity\Page p WHERE p.name LIKE :criteria OR p.slug LIKE :criteria OR p.id LIKE :criteria ORDER BY p.id ' . $order);
        $query->setParameter('criteria', '%' . $criteria . '%');

        return $query->getResult();
    }

    public function findByCriteriaByPageType(string $criteria, PageType $pageType, string $order = 'ASC'): array
    {
        $criteria = trim($criteria);
        $query = $this->em()->createQuery('SELECT p FROM App\Entity\Page p WHERE (p.name LIKE :criteria OR p.slug LIKE :criteria OR p.id LIKE :criteria) AND p.pageType = :pageType ORDER BY p.id ' . $order);
        $query->setParameter('criteria', '%' . $criteria . '%');
        $query->setParameter('pageType', $pageType);

        return $query->getResult();
    }

    public function findByState(string $state = PageStateEnum::PUBLISHED, string $order = 'ASC'): array
    {
        if (PageStateEnum::DRAFT_AND_PUBLISHED === $state) {
            return $this->findAllOrderBy($order);
        }

        $query = $this->em()->createQuery('SELECT p FROM App\Entity\Page p WHERE p.state = :state ORDER BY p.id ' . $order);
        $query->setParameter('state', $state);

        return $query->getResult();
    }

    public function findByStateAndPageType(PageType $pageType, string $state = PageStateEnum::PUBLISHED, string $order = 'ASC'): array
    {
        if (PageStateEnum::DRAFT_AND_PUBLISHED === $state) {
            return $this->findAllByPageTypeOrderBy($pageType, $order);
        }

        $query = $this->em()->createQuery('SELECT p FROM App\Entity\Page p WHERE p.state = :state AND p.pageType = :pageType ORDER BY p.id ' . $order);
        $query->setParameter('state', $state);
        $query->setParameter('pageType', $pageType);

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

        return (int)$query->getSingleScalarResult();
    }

    public function getPageFromDataNameDevKey(string $dataDevKeyName): ?Page
    {
        $dataEnum = $this->em()->createQuery(
            'SELECT de FROM App\Entity\DataEnum de
            WHERE de.name = :dataDevKeyName'
        );
        $dataEnum->setParameter('dataDevKeyName', $dataDevKeyName);

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
     * @throws NoResultException
     */
    public function getPageByTypeAndSlug(string $uri): ?Page
    {
        $segments = explode('/', trim($uri, '/'));
        $pageTypePrefix = '';
        $pageSlug = '';

        // Loop to find a matching pageTypePrefix
        for ($i = 0; $i < count($segments); $i++) {
            // Build the potentialPageTypePrefix
            $potentialPageTypePrefix = '/' . implode('/', array_slice($segments, 0, $i + 1));

            // If the potentialPageTypePrefix is "/backend", add it to the slug
            if ($potentialPageTypePrefix === '/backend') {
                $pageSlug = $potentialPageTypePrefix . '/' . implode('/', array_slice($segments, $i + 1));
                $pageSlug = substr($pageSlug, 1);
                break;
            }

            // Check if the potentialPageTypePrefix exists in the database
            $query = $this->em()->createQuery(
                'SELECT COUNT(pt) FROM App\Entity\PageType pt WHERE pt.urlPrefix = :prefix'
            );
            $query->setParameter('prefix', $potentialPageTypePrefix);

            if ($query->getSingleScalarResult() > 0) {
                $pageTypePrefix = $potentialPageTypePrefix;
                $pageSlug = implode('/', array_slice($segments, $i + 1));
                break;
            } else {
                $pageSlug = implode('/', array_slice($segments, $i));
            }
        }

        if (empty($pageTypePrefix) && empty($pageSlug)) {
            return null;
        }

        if (!empty($pageTypePrefix)) {
            $finalQuery = $this->em()->createQuery(
                'SELECT p FROM App\Entity\Page p
                JOIN p.pageType pt
                WHERE p.slug = :pageSlug AND pt.urlPrefix = :pageTypePrefix'
            );
            $finalQuery->setParameter('pageSlug', $pageSlug);
            $finalQuery->setParameter('pageTypePrefix', $pageTypePrefix);
        } else {
            $finalQuery = $this->em()->createQuery(
                'SELECT p FROM App\Entity\Page p
                    WHERE p.slug = :pageSlug'
            );
            $finalQuery->setParameter('pageSlug', $pageSlug);
        }

        return $finalQuery->getOneOrNullResult();
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

    /**
     * We take the highest devKey and increment it by 1 to get the next available devKey.
     * We make sure that the devKey is unique.
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function createDevKey(): int
    {
        $query = $this->em()->createQuery('SELECT MAX(p.devKey) FROM App\Entity\Page p');

        $devKey = $query->getSingleScalarResult();

        while ($this->findOneBy(['devKey' => $devKey])) {
            $devKey++;
        }

        return $devKey;
    }

    // Base

    private function em(): EntityManagerInterface
    {
        return $this->_em;
    }
}