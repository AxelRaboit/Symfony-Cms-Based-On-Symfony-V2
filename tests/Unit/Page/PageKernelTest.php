<?php

namespace App\Tests\Unit\Page;

use App\Entity\Image;
use App\Entity\Page;
use App\Entity\PageGallery;
use App\Entity\Website;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use TypeError;

class PageKernelTest extends KernelTestCase
{
    private Page $page;

    /**
     * Set up the entity to test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->page = new Page();
    }

    // State property

    public function testSetInvalidState(): void
    {
        $this->expectException(TypeError::class);

        $this->page->setState('invalid');

        $this->assertHasErrors($this->page, 1);
    }

    public function testSetState(): void
    {
        $this->page->setState(1);
        $this->assertEquals(1, $this->page->getState());
    }

    // Title property

    public function testSetTitle(): void
    {
        $this->page->setTitle('Test Title');
        $this->assertEquals('Test Title', $this->page->getTitle());
    }

    public function testSetInvalidTitle(): void
    {
        $this->expectException(TypeError::class);

        $this->page->setTitle(null);

        $this->assertHasErrors($this->page, 1);
    }

    // Name property

    public function testSetName(): void
    {
        $this->page->setName('Test Name');
        $this->assertEquals('Test Name', $this->page->getName());
    }

    public function testSetInvalidName(): void
    {
        $this->expectException(TypeError::class);

        $this->page->setName(null);

        $this->assertHasErrors($this->page, 1);
    }

    // Created And Updated At properties

    public function testCreatedAtAndUpdatedAt(): void
    {
        $date = new DateTimeImmutable();
        $this->page->setCreatedAt($date);
        $this->page->setUpdatedAt($date);

        $this->assertEquals($date, $this->page->getCreatedAt());
        $this->assertEquals($date, $this->page->getUpdatedAt());
    }

    // Children property

    public function testAddAndRemoveChild(): void
    {
        $childPage = new Page();
        $this->page->addChild($childPage);

        $this->assertTrue($this->page->getChildren()->contains($childPage));

        $this->page->removeChild($childPage);
        $this->assertNotContains($childPage, $this->page->getChildren()->toArray());
    }

    // Slug property

    public function testSetSlug(): void
    {
        $this->page->setSlug('test-slug');
        $this->assertEquals('test-slug', $this->page->getSlug());
    }

    public function testSetInvalidSlug(): void
    {
        $this->expectException(TypeError::class);

        $this->page->setSlug(null);

        $this->assertHasErrors($this->page, 1);
    }

    // Banner property

    public function testSetBanner(): void
    {
        $banner = new Image();
        $this->page->setBanner($banner);
        $this->assertEquals($banner, $this->page->getBanner());
    }

    public function testSetInvalidBanner(): void
    {
        $this->expectException(TypeError::class);

        // Wrong object type.
        $website = new Website();

        $this->page->setBanner($website);

        $this->assertHasErrors($this->page, 1);
    }

    // Thumbnail property

    public function testSetThumbnail(): void
    {
        $thumbnail = new Image();
        $this->page->setThumbnail($thumbnail);
        $this->assertEquals($thumbnail, $this->page->getThumbnail());
    }

    public function testSetInvalidThumbnail(): void
    {
        $this->expectException(TypeError::class);

        // Wrong object type.
        $website = new Website();

        $this->page->setThumbnail($website);

        $this->assertHasErrors($this->page, 1);
    }

    // Gallery property

    public function testAddAndRemoveGallery(): void
    {
        $gallery = new PageGallery();
        $this->page->addPageGallery($gallery);

        $this->assertTrue($this->page->getPageGalleries()->contains($gallery));

        $this->page->removePageGallery($gallery);
        $this->assertNotContains($gallery, $this->page->getPageGalleries()->toArray());
    }

    // ToArray method

    public function testToArray(): void
    {
        // Init necessary properties.
        $this->page->setDevKey(1);
        $this->page->setName('Test name');
        $this->page->setTitle('Test Title');
        $this->page->setSlug('test-slug');
        $this->page->setState(2);
        $this->page->setVisibleForBackendActions(true);
        $this->page->setDisplayType(1);

        $array = $this->page->toArray();

        $this->assertIsArray($array);

        $expectedKeys = [
            'id', 'template', 'devKey', 'devCodeRouteName', 'parent', 'children',
            'title', 'name', 'metaDescription', 'description', 'contentPrimary',
            'contentSecondary', 'contentTertiary', 'contentQuaternary', 'ctaTitle',
            'ctaText', 'ctaUrl', 'banner', 'thumbnail', 'slug', 'createdAt',
            'updatedAt', 'deletedAt', 'pageGalleries', 'canonicalUrl', 'pageType',
            'state', 'publishedAt', 'bannerTitle', 'visibleForBackendActions',
            'metaTitle', 'isSeoNoFollow', 'weight', 'displayType'
        ];
        $this->assertEquals($expectedKeys, array_keys($array));

        $this->assertEquals('Test Title', $array['title']);
        $this->assertEquals('Test name', $array['name']);
        $this->assertEquals('test-slug', $array['slug']);
        $this->assertEquals(2, $array['state']);
        $this->assertEquals(1, $array['displayType']);
        $this->assertEquals(1, $array['devKey']);
        $this->assertTrue($array['visibleForBackendActions']);
    }

    // Website property

    public function testSetWebsite(): void
    {
        $website = new Website();
        $this->page->setWebsite($website);
        $this->assertEquals($website, $this->page->getWebsite());
    }

    public function testSetInvalidWebsite(): void
    {
        $this->expectException(TypeError::class);

        // Wrong object type.
        $image = new Image();

        $this->page->setWebsite($image);

        $this->assertHasErrors($this->page, 1);
    }

    // base

    public function assertHasErrors(Page $page, int $number = 0): void
    {
        self::bootKernel();
        $container = static::$kernel->getContainer();

        $error = $container->get('validator')->validate($page);

        $this->assertCount($number, $error);
    }
}
