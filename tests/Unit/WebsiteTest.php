<?php

namespace App\Tests\Unit;

use App\Entity\Page;
use App\Entity\Website;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class WebsiteTest extends KernelTestCase
{
    public function getValidEntity(): Website
    {
        return (new Website())
            ->setEmail('test@test.com')
            ->setName('Test')
            ->setDomain('test.com')
            ->setHostname('test.com')
            ->setPort(80)
            ->setProtocol('https://')
        ;
    }

    /**
     * @throws \Exception
     */
    public function testEntityIsValid(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $website = $this->getValidEntity();

        $errors = $container->get('validator')->validate($website);

        $this->assertCount(0, $errors);
    }

    /**
     * @throws \Exception
     */
    public function testEntityIsNotValid(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $website = $this->getValidEntity();
        $website->setEmail('');
        $website->setName('');
        $website->setDomain('');
        $website->setHostname('');
        $website->setProtocol('');

        $errors = $container->get('validator')->validate($website);
        $this->assertCount(5, $errors);
    }

    public function testGetToArray(): void
    {
        $website = $this->getValidEntity();
        $this->assertIsArray($website->toArray());
    }
}
