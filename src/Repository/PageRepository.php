<?php

namespace App\Repository;

use App\Entity\Page;
use App\Entity\PageType;
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

    public function findByIsPublished(string $isPublished = 'true', string $order = 'ASC'): array
    {
        if ('true' === $isPublished) {
            $isPublished = true;
        } elseif ('false' === $isPublished) {
            $isPublished = false;
        }

        if ('both' === $isPublished) {
            return $this->findAllOrderBy($order);
        }

        $query = $this->em()->createQuery('SELECT p FROM App\Entity\Page p WHERE p.isPublished = :isPublished ORDER BY p.id ' . $order);
        $query->setParameter('isPublished', $isPublished);

        return $query->getResult();
    }

    public function findByIsPublishedByPageType(PageType $pageType, string $isPublished = 'true', string $order = 'ASC'): array
    {
        if ('true' === $isPublished) {
            $isPublished = true;
        } elseif ('false' === $isPublished) {
            $isPublished = false;
        }

        if ('both' === $isPublished) {
            return $this->findAllByPageTypeOrderBy($pageType, $order);
        }

        $query = $this->em()->createQuery('SELECT p FROM App\Entity\Page p WHERE p.isPublished = :isPublished AND p.pageType = :pageType ORDER BY p.id ' . $order);
        $query->setParameter('isPublished', $isPublished);
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

        // Boucle pour trouver un pageTypePrefix correspondant
        for ($i = 0; $i < count($segments); $i++) {
            // Construction du potentialPageTypePrefix
            $potentialPageTypePrefix = '/' . implode('/', array_slice($segments, 0, $i + 1));

            // Si le potentialPageTypePrefix est "/backend", l'ajouter au slug
            if ($potentialPageTypePrefix === '/backend') {
                $pageSlug = $potentialPageTypePrefix . '/' . implode('/', array_slice($segments, $i + 1));
                $pageSlug = substr($pageSlug, 1);
                break;
            }

            // Vérifier si le potentialPageTypePrefix existe dans la base de données
            $query = $this->em()->createQuery(
                'SELECT COUNT(pt) FROM App\Entity\PageType pt WHERE pt.urlPrefix = :prefix'
            );
            $query->setParameter('prefix', $potentialPageTypePrefix);

            if ($query->getSingleScalarResult() > 0) {
                $pageTypePrefix = $potentialPageTypePrefix;
                $pageSlug = implode('/', array_slice($segments, $i + 1));
                break;
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

        /*AND p.isPublished = true'*/

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