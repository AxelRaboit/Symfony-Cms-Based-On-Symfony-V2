<?php

namespace App\Tests\Unit\Website;

use App\Entity\Image;
use App\Entity\Page;
use App\Entity\PageGallery;
use App\Entity\Website;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use TypeError;

class WebsiteTest extends KernelTestCase
{
    private Website $website;

    /**
     * Set up the entity to test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->website = new Website();
    }

    // Name property

    public function testSetInvalidName(): void
    {
        $this->expectException(TypeError::class);

        $this->website->setName(null);

        $this->assertHasErrors($this->website, 1);
    }

    public function testSetName(): void
    {
        $this->website->setName('name');
        $this->assertEquals('name', $this->website->getName());
    }

    // Domain property

    public function testSetInvalidDomain(): void
    {
        $this->expectException(TypeError::class);

        $this->website->setDomain(null);

        $this->assertHasErrors($this->website, 1);
    }

    public function testSetDomain(): void
    {
        $this->website->setDomain('domain.com');
        $this->assertEquals('domain.com', $this->website->getDomain());
    }

    // Email property

    public function testSetInvalidEmail(): void
    {
        $this->expectException(TypeError::class);

        $this->website->setEmail(null);

        $this->assertHasErrors($this->website, 1);
    }

    public function testSetEmail(): void
    {
        $this->website->setEmail('test@test.com');
        $this->assertEquals('test@test.com', $this->website->getEmail());
    }

    // Hostname property

    public function testSetInvalidHostname(): void
    {
        $this->expectException(TypeError::class);

        $this->website->setHostname(null);

        $this->assertHasErrors($this->website, 1);
    }

    public function testSetHostname(): void
    {
        $this->website->setHostname('hostname');
        $this->assertEquals('hostname', $this->website->getHostname());
    }

    // Protocol property

    public function testSetInvalidProtocol(): void
    {
        $this->expectException(TypeError::class);

        $this->website->setProtocol(null);

        $this->assertHasErrors($this->website, 1);
    }

    public function testSetProtocol(): void
    {
        $this->website->setProtocol(80);
        $this->assertEquals(80, $this->website->getProtocol());
    }

    // Page Collection property

    public function testAddAndRemovePage(): void
    {
        $page = new Page();
        $this->website->addPage($page);

        $this->assertTrue($this->website->getPages()->contains($page));

        $this->website->removePage($page);
        $this->assertNotContains($page, $this->website->getPages()->toArray());
    }

    // base

    public function assertHasErrors(Website $website, int $number = 0): void
    {
        self::bootKernel();
        $container = static::$kernel->getContainer();

        $error = $container->get('validator')->validate($website);

        $this->assertCount($number, $error);
    }
}
