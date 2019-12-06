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
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class OperationAccessTest extends ApiTestCase
{
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

        // get a user
        $this->user = $this->entityManager
        ->getRepository(User::class)
        ->findFirstBy('ROLE_USER');

        // get a valid token
        $client = static::createClient();
        $crawler = $client->request('POST', '/authentication_token', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'username' => $this->user->getUsername(),
                'password' => 'user'
            ],
        ]);
        $jsonResponse = json_decode($crawler->getContent(), true);
        $this->token = $jsonResponse['token'];

    }

    /**
     * Test if post on product routes fails
     */
    public function testUnauthorizedPostOperations()
    {

        // create the client
        $client = static::createClient();

        // brands
        $crawler = $client->request('POST', '/api/brands', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ],
            'json' => ['name' => 'Test'],
        ]);
        $this->assertResponseStatusCodeSame('405');

        // categories
        $crawler = $client->request('POST', '/api/categories', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ],
            'json' => ['name' => 'Test'],
        ]);
        $this->assertResponseStatusCodeSame('405');

        // colors
        $crawler = $client->request('POST', '/api/colors', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ],
            'json' => ['name' => 'Test'],
        ]);
        $this->assertResponseStatusCodeSame('405');

        // media
        $crawler = $client->request('POST', '/api/media', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ],
            'json' => ['url' => 'test.jpg'],
        ]);
        $this->assertResponseStatusCodeSame('405');

        // products
        $crawler = $client->request('POST', '/api/products', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ],
            'json' => ['name' => 'Test'],
        ]);
        $this->assertResponseStatusCodeSame('405');

    }

    /**
     * Test if put on product routes fails
     */
    public function testUnauthorizedPutOperations()
    {

        // create the client
        $client = static::createClient();

        // brands
        $crawler = $client->request('PUT', '/api/brands/' . $this->entityManager->getRepository(Brand::class)->findFirstId(), [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ],
            'json' => ['name' => 'Test'],
        ]);
        $this->assertResponseStatusCodeSame('405');

        // categories
        $crawler = $client->request('PUT', '/api/categories/' . $this->entityManager->getRepository(Category::class)->findFirstId(), [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ],
            'json' => ['name' => 'Test'],
        ]);
        $this->assertResponseStatusCodeSame('405');

        // colors
        $crawler = $client->request('PUT', '/api/colors/' . $this->entityManager->getRepository(Color::class)->findFirstId(), [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ],
            'json' => ['name' => 'Test'],
        ]);
        $this->assertResponseStatusCodeSame('405');

        // media
        $crawler = $client->request('PUT', '/api/media/' . $this->entityManager->getRepository(Media::class)->findFirstId(), [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ],
            'json' => ['url' => 'test.jpg'],
        ]);
        $this->assertResponseStatusCodeSame('405');

        // products
        $crawler = $client->request('PUT', '/api/products/' . $this->entityManager->getRepository(Product::class)->findFirstId(), [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ],
            'json' => ['name' => 'Test'],
        ]);
        $this->assertResponseStatusCodeSame('405');

    }

    /**
     * Test if put on product routes fails
     */
    public function testUnauthorizedDeleteOperations()
    {

        // create the client
        $client = static::createClient();

        // brands
        $crawler = $client->request('DELETE', '/api/brands/' . $this->entityManager->getRepository(Brand::class)->findFirstId(), [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ]
        ]);
        $this->assertResponseStatusCodeSame('405');

        // categories
        $crawler = $client->request('DELETE', '/api/categories/' . $this->entityManager->getRepository(Category::class)->findFirstId(), [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ]
        ]);
        $this->assertResponseStatusCodeSame('405');

        // colors
        $crawler = $client->request('DELETE', '/api/colors/' . $this->entityManager->getRepository(Color::class)->findFirstId(), [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ]
        ]);
        $this->assertResponseStatusCodeSame('405');

        // media
        $crawler = $client->request('DELETE', '/api/media/' . $this->entityManager->getRepository(Media::class)->findFirstId(), [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ]
        ]);
        $this->assertResponseStatusCodeSame('405');

        // products
        $crawler = $client->request('DELETE', '/api/products/' . $this->entityManager->getRepository(Product::class)->findFirstId(), [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ]
        ]);
        $this->assertResponseStatusCodeSame('405');

    }
   

}