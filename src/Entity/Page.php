<?php

namespace App\Entity;

use App\Repository\PageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PageRepository::class)]
#[ORM\UniqueConstraint(name: 'slug_unique', columns: ['slug'])]
class Page
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $template = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $contentPrimary = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $contentSecondary = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $contentTertiary = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $contentQuaternary = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $devCodeRouteName = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $ctaTitle = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $ctaText = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ctaUrl = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    private ?self $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    private Collection $children;

    #[ORM\Column(length: 255)]
    private string $slug;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $devKey = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $metaDescription = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $canonicalUrl = null;

    #[ORM\ManyToOne(inversedBy: 'pages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PageType $pageType = null;

    #[ORM\ManyToOne(inversedBy: 'pageBanners')]
    private ?Image $banner = null;

    #[ORM\ManyToOne(inversedBy: 'pageThumbnails')]
    private ?Image $thumbnail = null;

    #[ORM\OneToMany(mappedBy: 'page', targetEntity: PageGallery::class)]
    private Collection $pageGalleries;

    #[ORM\ManyToOne(inversedBy: 'pages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Website $website = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $publishedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $bannerTitle = null;

    #[ORM\Column]
    private bool $visibleForBackendActions;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $metaTitle = null;

    #[ORM\Column]
    private int $weight = 0;

    #[ORM\Column(length: 255)]
    private string $displayType;

    #[ORM\Column]
    private int $state;

    #[ORM\Column]
    private bool $isSeoNoFollow = false;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->pageGalleries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setTemplate(?string $template): static
    {
        $this->template = $template;

        return $this;
    }

    public function getContentPrimary(): ?string
    {
        return $this->contentPrimary;
    }

    public function setContentPrimary(?string $contentPrimary): static
    {
        $this->contentPrimary = $contentPrimary;

        return $this;
    }

    public function getContentSecondary(): ?string
    {
        return $this->contentSecondary;
    }

    public function setContentSecondary(?string $contentSecondary): static
    {
        $this->contentSecondary = $contentSecondary;

        return $this;
    }

    public function getContentTertiary(): ?string
    {
        return $this->contentTertiary;
    }

    public function setContentTertiary(?string $contentTertiary): static
    {
        $this->contentTertiary = $contentTertiary;

        return $this;
    }

    public function getContentQuaternary(): ?string
    {
        return $this->contentQuaternary;
    }

    public function setContentQuaternary(?string $contentQuaternary): static
    {
        $this->contentQuaternary = $contentQuaternary;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDevCodeRouteName(): ?string
    {
        return $this->devCodeRouteName;
    }

    public function setDevCodeRouteName(?string $devCodeRouteName): static
    {
        $this->devCodeRouteName = $devCodeRouteName;

        return $this;
    }

    public function getCtaTitle(): ?string
    {
        return $this->ctaTitle;
    }

    public function setCtaTitle(?string $ctaTitle): static
    {
        $this->ctaTitle = $ctaTitle;

        return $this;
    }

    public function getCtaText(): ?string
    {
        return $this->ctaText;
    }

    public function setCtaText(?string $ctaText): static
    {
        $this->ctaText = $ctaText;

        return $this;
    }

    public function getCtaUrl(): ?string
    {
        return $this->ctaUrl;
    }

    public function setCtaUrl(?string $ctaUrl): static
    {
        $this->ctaUrl = $ctaUrl;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): static
    {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(self $child): static
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeImmutable $deletedAt): static
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function getDevKey(): ?int
    {
        return $this->devKey;
    }

    public function setDevKey(?int $devKey): static
    {
        $this->devKey = $devKey;

        return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(?string $metaDescription): static
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    public function getCanonicalUrl(): ?string
    {
        return $this->canonicalUrl;
    }

    public function setCanonicalUrl(?string $canonicalUrl): static
    {
        $this->canonicalUrl = $canonicalUrl;

        return $this;
    }

    public function getPageType(): ?PageType
    {
        return $this->pageType;
    }

    public function setPageType(?PageType $pageType): static
    {
        $this->pageType = $pageType;

        return $this;
    }

    public function getBanner(): ?Image
    {
        return $this->banner;
    }

    public function setBanner(?Image $banner): static
    {
        $this->banner = $banner;

        return $this;
    }

    public function getThumbnail(): ?Image
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?Image $thumbnail): static
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    /**
     * @return Collection<int, PageGallery>
     */
    public function getPageGalleries(): Collection
    {
        return $this->pageGalleries;
    }

    public function addPageGallery(PageGallery $pageGallery): static
    {
        if (!$this->pageGalleries->contains($pageGallery)) {
            $this->pageGalleries->add($pageGallery);
            $pageGallery->setPage($this);
        }

        return $this;
    }

    public function removePageGallery(PageGallery $pageGallery): static
    {
        if ($this->pageGalleries->removeElement($pageGallery)) {
            // set the owning side to null (unless already changed)
            if ($pageGallery->getPage() === $this) {
                $pageGallery->setPage(null);
            }
        }

        return $this;
    }

    // Others

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'template' => $this->getTemplate(),
            'devKey' => $this->getDevKey(),
            'devCodeRouteName' => $this->getDevCodeRouteName(),
            'parent' => $this->getParent(),
            'children' => $this->getChildren(),
            'title' => $this->getTitle(),
            'name' => $this->getName(),
            'metaDescription' => $this->getMetaDescription(),
            'description' => $this->getDescription(),
            'contentPrimary' => $this->getContentPrimary(),
            'contentSecondary' => $this->getContentSecondary(),
            'contentTertiary' => $this->getContentTertiary(),
            'contentQuaternary' => $this->getContentQuaternary(),
            'ctaTitle' => $this->getCtaTitle(),
            'ctaText' => $this->getCtaText(),
            'ctaUrl' => $this->getCtaUrl(),
            'banner' => $this->getBanner(),
            'thumbnail' => $this->getThumbnail(),
            'slug' => $this->getSlug(),
            'createdAt' => $this->getCreatedAt(),
            'updatedAt' => $this->getUpdatedAt(),
            'deletedAt' => $this->getDeletedAt(),
            'pageGalleries' => $this->getPageGalleries(),
            'canonicalUrl' => $this->getCanonicalUrl(),
            'pageType' => $this->getPageType(),
            'state' => $this->getState(),
            'publishedAt' => $this->getPublishedAt(),
            'bannerTitle' => $this->getBannerTitle(),
            'visibleForBackendActions' => $this->isVisibleForBackendActions(),
            'metaTitle' => $this->getMetaTitle(),
            'isSeoNoFollow' => $this->isSeoNoFollow(),
            'weight' => $this->getWeight(),
            'displayType' => $this->getDisplayType(),
        ];
    }

    public function getWebsite(): ?Website
    {
        return $this->website;
    }

    public function setWebsite(?Website $website): static
    {
        $this->website = $website;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeImmutable $publishedAt): static
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getBannerTitle(): ?string
    {
        return $this->bannerTitle;
    }

    public function setBannerTitle(?string $bannerTitle): static
    {
        $this->bannerTitle = $bannerTitle;

        return $this;
    }

    public function isVisibleForBackendActions(): bool
    {
        return $this->visibleForBackendActions;
    }

    public function setVisibleForBackendActions(bool $visibleForBackendActions): static
    {
        $this->visibleForBackendActions = $visibleForBackendActions;

        return $this;
    }

    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    public function setMetaTitle(?string $metaTitle): static
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function getDisplayType(): string
    {
        return $this->displayType;
    }

    public function setDisplayType(string $displayType): static
    {
        $this->displayType = $displayType;

        return $this;
    }

    public function getState(): int
    {
        return $this->state;
    }

    public function setState(int $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function isSeoNoFollow(): bool
    {
        return $this->isSeoNoFollow;
    }

    public function setIsSeoNoFollow(bool $isSeoNoFollow): static
    {
        $this->isSeoNoFollow = $isSeoNoFollow;

        return $this;
    }
}
