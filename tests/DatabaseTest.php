<?php

namespace App\Tests;

use App\Entity\User;
use App\Entity\Brand;
use App\Entity\Color;
use App\Entity\Media;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Customer;
use App\Entity\CustomerAddress;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;

class DatabaseTest extends WebTestCase
{
    use FixturesTrait;

    protected $entityManager;
    protected $token;
    protected $user;

    /**
     * Set up the test
     *
     * @return void
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        // get doctrine
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->loadFixtures([
            'App\DataFixtures\ProductFixtures',
            'App\DataFixtures\UserFixtures'
        ]);
    }

    public function testFixtures()
    {

        // Brands
        $firstId = $this->entityManager
            ->getRepository(Product::class)
            ->findFirstId();
        $this->assertIsInt($firstId);

        // Category
        $firstId = $this->entityManager
            ->getRepository(Category::class)
            ->findFirstId();
        $this->assertIsInt($firstId);

        // Color
        $firstId = $this->entityManager
            ->getRepository(Color::class)
            ->findFirstId();
        $this->assertIsInt($firstId);

        // User
        $firstUser = $this->entityManager
            ->getRepository(User::class)
            ->findFirstBy('ROLE_USER');
        $this->assertIsInt($firstUser->getId());

        // Customer
        $firstCustomerId = $this->entityManager
            ->getRepository(Customer::class)
            ->findFirstIdBy($firstUser);
        $this->assertIsInt($firstCustomerId);

        // CustomerAddress
        $firstId = $this->entityManager
            ->getRepository(CustomerAddress::class)
            ->findFirstIdBy($firstCustomerId);
        $this->assertIsInt($firstId);

        // Media
        $firstId = $this->entityManager
            ->getRepository(Media::class)
            ->findFirstId();
        $this->assertIsInt($firstId);

        // Product
        $firstId = $this->entityManager
            ->getRepository(Product::class)
            ->findFirstId();
        $this->assertIsInt($firstId);
    }

}