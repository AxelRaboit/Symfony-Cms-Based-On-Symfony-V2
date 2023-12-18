<?php

namespace App\Tests\Unit;

use App\Entity\Page;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class PageTest extends TestCase
{
    private Page $page;

    protected function setUp(): void
    {
        $this->page = new Page();
    }

    public function testGetName(): void
    {
        $this->page->setName('Test name');
        $this->assertEquals('Test name', $this->page->getName());
    }

    public function testGetTitle(): void
    {
        $this->page->setTitle('Test Title');
        $this->assertEquals('Test Title', $this->page->getTitle());
    }

    public function testCreatedAtAndUpdatedAt(): void
    {
        $date = new DateTimeImmutable();
        $this->page->setCreatedAt($date);
        $this->page->setUpdatedAt($date);

        $this->assertEquals($date, $this->page->getCreatedAt());
        $this->assertEquals($date, $this->page->getUpdatedAt());
    }

    public function testGetState(): void
    {
        $this->page->setState(1);
        $this->assertEquals(1, $this->page->getState());
    }

    public function testAddAndRemoveChild(): void
    {
        $childPage = new Page();
        $this->page->addChild($childPage);

        $this->assertTrue($this->page->getChildren()->contains($childPage));

        $this->page->removeChild($childPage);
        $this->assertNotContains($childPage, $this->page->getChildren()->toArray());
    }

}