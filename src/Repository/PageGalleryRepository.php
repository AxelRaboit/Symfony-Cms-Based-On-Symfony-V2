<?php

namespace App\Repository;

use App\Entity\Page;
use App\Entity\PageGallery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PageGallery>
 *
 * @method PageGallery|null find($id, $lockMode = null, $lockVersion = null)
 * @method PageGallery|null findOneBy(array $criteria, array $orderBy = null)
 * @method PageGallery[]    findAll()
 * @method PageGallery[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageGalleryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PageGallery::class);
    }

    /**
     * Retrieves a gallery associated with a page.
     *
     * @return array<int|string, array<string, mixed>>
     */
    public function getPageGallery(Page $page): array
    {
        $data = [];

        /** @var PageGallery[] $result */
        $result = $this->em()->createQuery(
            'SELECT pg FROM App\Entity\PageGallery pg WHERE pg.page = :page'
        )->setParameter('page', $page)->getResult();

        foreach ($result as $item) {
            $data[$item->getId()] = [
                'title' => $item->getTitle() ?: null,
                'subTitle' => $item->getSubTitle() ?: null,
                'description' => $item->getDescription() ?: null,
                'imageAlt' => $item->getImageAlt() ?: null,
                'imageUrl' => $item->getImageUrl() ?: null,
                'imageTitle' => $item->getImageTitle() ?: null,
                'ctaText' => $item->getCtaText() ?: null,
                'ctaTitle' => $item->getCtaTitle() ?: null,
                'ctaUrl' => $item->getCtaUrl() ?: null,
                'weight' => $item->getWeight() ?: null,
                'image' => $item->getImage(),
            ];
        }

        return $data;
    }

    // Base

    private function em(): EntityManagerInterface
    {
        return $this->_em;
    }
}
