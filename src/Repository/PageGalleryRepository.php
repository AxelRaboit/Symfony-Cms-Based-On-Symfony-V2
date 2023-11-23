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

    public function getPageGallery(Page $page): array
    {
        $data = [];

        $pageGallery = $this->em()->createQuery(
            'SELECT pg FROM App\Entity\PageGallery pg
            WHERE pg.page = :page'
        );
        $pageGallery->setParameter('page', $page);

        $result = $pageGallery->getResult();
        if ([] !== $result) {
            $result = $pageGallery->getResult();

            foreach ($result as $item) {
                $data[$item->getId()] = [
                    'title' => $item->getTitle(),
                    'subTitle' => $item->getSubTitle(),
                    'description' => $item->getDescription(),
                    'imageAlt' => $item->getImageAlt(),
                    'imageUrl' => $item->getImageUrl(),
                    'imageTitle' => $item->getImageTitle(),
                    'ctaText' => $item->getCtaText(),
                    'ctaTitle' => $item->getCtaTitle(),
                    'ctaUrl' => $item->getCtaUrl(),
                    'weight' => $item->getWeight(),
                    'image' => $item->getImage(),
                ];
            }

            return $data;
        }

        return $data;
    }

    // Base

    private function em(): EntityManagerInterface
    {
        return $this->_em;
    }
}
