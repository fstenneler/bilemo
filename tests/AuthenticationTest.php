<?php

namespace App\Tests;

use App\Entity\User;
use App\Entity\CustomerAddress;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Authentication extends ApiTestCase
{
    protected $entityManager;
    protected $accessToken;

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

    }

    /**
     * Test if the authentication process with correct credentials retrives a JWT token
     * Test if the authentication process with wrong credentials fails
     */
    public function testGenerateAccessToken(): void
    {   

        // get a user from database
        $user = $this->entityManager
            ->getRepository(User::class)
            ->findFirstBy('ROLE_USER');

        // create the client
        $client = static::createClient();

        // test connection with a wrong password
        $crawler = $client->request('POST', '/authentication_token', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'username' => $user->getUsername(),
                'password' => 'aaa'
            ],
        ]);
        $this->assertResponseStatusCodeSame('401');

        // test connection with a good password
        $crawler = $client->request('POST', '/authentication_token', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'username' => $user->getUsername(),
                'password' => 'user'
            ],
        ]);

        // convert json response to php array 
        // usage of $this->assertJsonContains() is not possible here, because not compatible with PHPUnit v <8.* and PHPUnit v >=8.* is not compatible with Symfony
        $jsonResponse = json_decode($crawler->getContent(), true);

        // test if response contains token
        $this->assertArrayHasKey('token', $jsonResponse);

    }

    /**
     * Test if access to all routes is possible with a valid token
     * Test is access to all routes is not possible without a valid token
     */
    public function testAccessToRoutes(): void
    {   

        // get a user from database
        $user = $this->entityManager
            ->getRepository(User::class)
            ->findFirstBy('ROLE_USER');

        // create the client
        $client = static::createClient();

        // connect user and get token
        $crawler = $client->request('POST', '/authentication_token', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'username' => $user->getUsername(),
                'password' => 'user'
            ],
        ]);

        // convert json response to php array 
        $jsonResponse = json_decode($crawler->getContent(), true);
        $token = $jsonResponse['token'];

        // create an array with routes
        $routes = array(
            '/api/brands',
            '/api/categories',
            '/api/colors',
            '/api/customers',
            '/api/media',
            '/api/products'
        );

        // test each route
        foreach($routes as $route) {

            // test if response is successful with correct token
            $crawler = $client->request('GET', $route, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $token
                ],
            ]);
            $this->assertResponseIsSuccessful();

            // test if connection without access token fails
            $crawler = $client->request('GET', $route, [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
            ]);
            $this->assertResponseStatusCodeSame('401');

        }

    }

}