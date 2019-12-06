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

class OperationTest extends ApiTestCase
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
     * Test post a new customer
     */
    public function testPostCustomer()
    {

        // create the client
        $client = static::createClient();

        $crawler = $client->request('POST', '/api/customers', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ],
            'json' => [
                "email" => "test@orinstreet.rocks",
                "password" => "aaaaaa",
                "birthday" => "2019-12-06T14:26:31.275Z",
                "phone" => "06123456789"
            ],
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertRegExp('#^/api/customers/([0-9]+)$#', $crawler->toArray()['@id']);

    }

    /**
     * Test post a new customerAddress
     */
    public function testPostCustomerAddress()
    {

        // get a customer for current user
        $customerId = $this->entityManager
            ->getRepository(Customer::class)
            ->findFirstIdBy($this->user);

        // create the client
        $client = static::createClient();

        $crawler = $client->request('POST', '/api/customer_addresses', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ],
            'json' => [
                "firstName" => "test",
                "lastName" => "test",
                "business" => "test",
                "addressLine1" => "test",
                "addressLine2" => "test",
                "postalCode" => "123456",
                "city" => "test",
                "country" => "France",
                "customerId" => $customerId
            ],
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertRegExp('#^/api/customer_addresses/([0-9]+)$#', $crawler->toArray()['@id']);

    }

    /**
     * Test put for an existing customer
     */
    public function testPutCustomer()
    {

        // get a customer for current user
        $customerId = $this->entityManager
            ->getRepository(Customer::class)
            ->findFirstIdBy($this->user);

        // create the client
        $client = static::createClient();

        $crawler = $client->request('PUT', '/api/customers/' . $customerId, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ],
            'json' => [
                "email" => "test@orinstreet.rocks",
                "password" => "aaaaaa",
                "birthday" => "2019-12-06T14:26:31.275Z",
                "phone" => "06123456789"
            ],
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertSame('/api/customers/' . $customerId, $crawler->toArray()['@id']);

    }

    /**
     * Test put for an existing customerAddress
     */
    public function testPutCustomerAddress()
    {

        // get a customer for current user
        $customerId = $this->entityManager
            ->getRepository(Customer::class)
            ->findFirstIdBy($this->user);

        // get a customerAddress for current user
        $customerAddressId = $this->entityManager
            ->getRepository(CustomerAddress::class)
            ->findFirstIdBy($this->user);

        // create the client
        $client = static::createClient();

        $crawler = $client->request('PUT', '/api/customer_addresses/' . $customerAddressId, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ],
            'json' => [
                "firstName" => "test",
                "lastName" => "test",
                "business" => "test",
                "addressLine1" => "test",
                "addressLine2" => "test",
                "postalCode" => "123456",
                "city" => "test",
                "country" => "France",
                "customerId" => $customerId
            ],
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertSame('/api/customer_addresses/' . $customerAddressId, $crawler->toArray()['@id']);

    }

    /**
     * Test deleting an existing customer
     */
    public function testDeleteCustomer()
    {

        // get a customer for current user
        $customerId = $this->entityManager
            ->getRepository(Customer::class)
            ->findFirstIdBy($this->user);

        // create the client
        $client = static::createClient();

        $crawler = $client->request('DELETE', '/api/customers/' . $customerId, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ],
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame('204');

    }

    /**
     * Test deleting an existing customerAddress
     */
    public function testDeleteCustomerAddress()
    {

        // get a customer for current user
        $customerId = $this->entityManager
            ->getRepository(Customer::class)
            ->findFirstIdBy($this->user);

        // get a customerAddress for current user
        $customerAddressId = $this->entityManager
            ->getRepository(CustomerAddress::class)
            ->findFirstIdBy($this->user);

        // create the client
        $client = static::createClient();

        $crawler = $client->request('DELETE', '/api/customer_addresses/' . $customerAddressId, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ],
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame('204');

    }   

}