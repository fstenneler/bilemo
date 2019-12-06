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

class RouteTest extends ApiTestCase
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
     * Test each collection route : accessibility, Json format and id
     */
    public function testGetCollections()
    {

        // create an array with routes
        $collectionRoutes = array(
            '/api/brands',
            '/api/categories',
            '/api/colors',
            '/api/customers',
            '/api/media',
            '/api/products'
        );

        // create the client
        $client = static::createClient();

        // test each route
        foreach($collectionRoutes as $route) {

            // test if response is successful with correct token
            $crawler = $client->request('GET', $route, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->token
                ],
            ]);
            $this->assertResponseIsSuccessful();

            // Asserts that the returned content type is JSON
            $this->assertJson($crawler->getContent());

            // convert json response to php array 
            // usage of $this->assertJsonContains() is not possible here, because not compatible with PHPUnit v <8.* and PHPUnit v >=8.* is not compatible with Symfony
            $jsonResponse = json_decode($crawler->getContent(), true);

            // test response content
            $this->assertContains($route, $jsonResponse['@id']);
            $this->assertContains('hydra:Collection', $jsonResponse['@type']);

            // test pagination
            if($jsonResponse['hydra:totalItems'] > 5) {
                $this->assertContains($route, $jsonResponse['hydra:view']['@id']);
                $this->assertContains($route . '?page=1', $jsonResponse['hydra:view']['hydra:first']);
                $this->assertContains($route . '?page=2', $jsonResponse['hydra:view']['hydra:next']);
            }

        }

    }

    /**
     * Test each item route : accessibility, Json format and id
     */
    public function testGetItems()
    {

        // find the first id for each route
        $itemRoutes = array(
            '/api/brands/' . $this->entityManager->getRepository(Brand::class)->findFirstId(),
            '/api/categories/' . $this->entityManager->getRepository(Category::class)->findFirstId(),
            '/api/colors/' . $this->entityManager->getRepository(Color::class)->findFirstId(),
            '/api/customer_addresses/' . $this->entityManager->getRepository(CustomerAddress::class)->findFirstIdBy($this->user),
            '/api/customers/' . $this->entityManager->getRepository(Customer::class)->findFirstIdBy($this->user),
            '/api/media/' . $this->entityManager->getRepository(Media::class)->findFirstId(),
            '/api/products/' . $this->entityManager->getRepository(Product::class)->findFirstId()
        );

        // create the client
        $client = static::createClient();

        // test each route
        foreach($itemRoutes as $route) {

            // test if response is successful with correct token
            $crawler = $client->request('GET', $route, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->token
                ],
            ]);
            $this->assertResponseIsSuccessful();

            // Asserts that the returned content type is JSON
            $this->assertJson($crawler->getContent());

            // convert json response to php array 
            // usage of $this->assertJsonContains() is not possible here, because not compatible with PHPUnit v <8.* and PHPUnit v >=8.* is not compatible with Symfony
            $jsonResponse = json_decode($crawler->getContent(), true);

            // test response content
            $this->assertContains($route, $jsonResponse['@id']);

        }


    }
   

}