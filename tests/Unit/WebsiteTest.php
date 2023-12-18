<?php

namespace App\Tests\Unit;

use App\Entity\Page;
use App\Entity\Website;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class WebsiteTest extends KernelTestCase
{
    /**
     * Get a valid instance of the Website entity.
     *
     * @return Website The valid instance of the Website entity with predefined values.
     */
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
     * Test the entityIsValid method of the TestClass class.
     * This method tests if the given entity is considered valid according to the Symfony Validator.
     *
     * @return void
     *
     * @throws \Exception if the service 'validator' is not an instance of \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    public function testEntityIsValid(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $website = $this->getValidEntity();

        $validator = $container->get('validator');

        if (!$validator instanceof ValidatorInterface) {
            throw new \Exception('Service validator must be instance of ValidatorInterface');
        }

        $errors = $validator->validate($website);

        $this->assertCount(0, $errors);
    }

    /**
     * Test the testEntityIsNotValid method of the ClassName class.
     * This method tests if the testEntityIsNotValid correctly validates an invalid entity object and returns the correct number of errors.
     *
     * @return void
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

        $validator = $container->get('validator');

        if (!$validator instanceof ValidatorInterface) {
            throw new \Exception('Service validator must be instance of ValidatorInterface');
        }

        $errors = $validator->validate($website);

        $this->assertCount(5, $errors);
    }

    /**
     * Test the getToArray method of the Website class.
     * This method tests if the toArray method of the Website class correctly returns an array representation of the website entity.
     *
     * @return void
     */
    public function testGetToArray(): void
    {
        $website = $this->getValidEntity();
        $this->assertIsArray($website->toArray());
    }

    /**
     * Test method to verify the functionality of adding a page to a website.
     *
     * @return void
     * @throws \Exception if an error occurs during the test execution.
     */
    public function testAddPage(): void
    {
        $website = $this->getValidEntity();
        $page = new Page();
        $website->addPage($page);

        $this->assertTrue($website->getPages()->contains($page));
    }

    /**
     * Test case for the method removePage.
     *
     * @return void
     * @throws \Exception
     */
    public function testRemovePage(): void
    {
        $website = $this->getValidEntity();
        $page = new Page();

        $website->addPage($page);
        $website->removePage($page);

        $this->assertFalse($website->getPages()->contains($page));
    }

    /**
     * Test case for the method getPages.
     *
     * @return void
     * @throws \Exception If an error occurs.
     *
     */
    public function testGetPages(): void
    {
        $website = $this->getValidEntity();
        $page1 = new Page();
        $page2 = new Page();

        $website->addPage($page1);
        $website->addPage($page2);

        $this->assertCount(2, $website->getPages());
    }
}
