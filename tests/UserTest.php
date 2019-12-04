<?php

namespace App\Tests;

use App\Entity\User;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class UserTest extends ApiTestCase
{
    protected $entityManager;
    protected $accessToken;

    /**
     * Set up the test
     * Purge database and load fixtures
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

    public function testGenerateAccessToken(): void
    {   

        // get a user
        $user = $this->entityManager
            ->getRepository(User::class)
            ->findFirstUser('ROLE_USER');

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

        // test if route exists
        $this->assertResponseIsSuccessful();

        // Asserts that the returned content type is JSON
        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        // convert json response to php array 
        // usage of $this->assertJsonContains() is not possible here, because not compatible with PHPUnit v <8.* and PHPUnit v >=8.* is not compatible with Symfony
        $jsonResponse = json_decode($crawler->getContent(), true);

        // test if response contains token
        $this->assertArrayHasKey('token', $jsonResponse);

        //dump(get_class_methods(ApiTestCase::class));

    }

}