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

class OwnerAccessTest extends ApiTestCase
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
     * Test if a user access only to his own customer collections
     */
    public function testCollection()
    {

        // create the client
        $client = static::createClient();

        //customer collection
        $crawler = $client->request('GET', '/api/customers', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ]
        ]);
        $jsonResponse = json_decode($crawler->getContent(), true);

        // extract the returned customer ids
        $returnedCustomersId = array();
        foreach($jsonResponse['hydra:member'] as $line) {
            $returnedCustomerId[] = (int) preg_replace("#^/api/customers/#", "", $line['@id']);
        }
        sort($returnedCustomerId);

        // find the real customer ids for the current user
        $expectedCustomers = $this->entityManager
            ->getRepository(Customer::class)
            ->findIdByUser($this->user, 5);
        sort($expectedCustomers);

        // test if both arrays are the same
        $this->assertSame($expectedCustomers, $returnedCustomerId);

    }

    /**
     * Test if a user access only to his own customer items
     */
    public function testItem()
    {

        // create the client
        $client = static::createClient();

        // test with the cutomer owner
        $route = '/api/customers/' . $this->entityManager->getRepository(Customer::class)->findFirstIdBy($this->user);
        $crawler = $client->request('GET', $route, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ]
        ]);
        $jsonResponse = json_decode($crawler->getContent(), true);

        // test if returned id is correct
        $this->assertSame($jsonResponse['@id'], $route);

        // test with another user
        $otherUser = $this->entityManager
            ->getRepository(User::class)
            ->findAnotherOne('ROLE_USER', $this->user->getId());

        $route = '/api/customers/' . $this->entityManager->getRepository(Customer::class)->findFirstIdBy($otherUser);

        $crawler = $client->request('GET', $route, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ]
        ]);

        // test if returned id is correct
        $this->assertResponseStatusCodeSame('404');

    }
   

}