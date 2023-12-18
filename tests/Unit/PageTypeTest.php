<?php

namespace App\Tests\Unit;

use App\Entity\Page;
use App\Entity\PageType;
use PHPUnit\Framework\TestCase;

class PageTypeTest extends TestCase
{
    private PageType $pageType;

    protected function setUp(): void
    {
        $this->pageType = new PageType();
    }

    public function testSetValidName(): void
    {
        $this->pageType->setName('Test name');
        $this->assertEquals('Test name', $this->pageType->getName());
    }

    public function testSetValidDevkey(): void
    {
        $this->pageType->setDevKey(1);
        $this->assertEquals(1, $this->pageType->getDevKey());
    }

    public function testAddValidPages(): void
    {
        $page = new Page();
        $this->pageType->addPage($page);
        $this->assertEquals([$page], $this->pageType->getPages()->toArray());
    }

    public function testAddAndRemoveValidPage(): void
    {
        $page = new Page();
        $this->pageType->addPage($page);

        $this->assertTrue($this->pageType->getPages()->contains($page));

        $this->pageType->removePage($page);
        $this->assertNotContains($page, $this->pageType->getPages()->toArray());
    }

    public function testSetValidCreatedAt(): void
    {
        $date = new \DateTimeImmutable();
        $this->pageType->setCreatedAt($date);
        $this->assertEquals($date, $this->pageType->getCreatedAt());
    }

    public function testSetValidUpdatedAt(): void
    {
        $date = new \DateTimeImmutable();
        $this->pageType->setUpdatedAt($date);
        $this->assertEquals($date, $this->pageType->getUpdatedAt());
    }

    public function testSetValidDeletedAt(): void
    {
        $date = new \DateTimeImmutable();
        $this->pageType->setDeletedAt($date);
        $this->assertEquals($date, $this->pageType->getDeletedAt());
    }

    public function testSetValidTemplate(): void
    {
        $this->pageType->setTemplate('template');
        $this->assertEquals('template', $this->pageType->getTemplate());
    }

    public function testSetValidUrlPrefix(): void
    {
        $this->pageType->setUrlPrefix('my-url-prefix');
        $this->assertEquals('my-url-prefix', $this->pageType->getUrlPrefix());
    }
}
