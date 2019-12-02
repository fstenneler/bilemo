<?php

namespace App\Tests;

use App\Entity\Brand;
use App\Entity\Color;
use App\Entity\Product;
use App\Entity\Category;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class ProductTest extends ApiTestCase
{

    public function testProduct(): void
    {
        $this->runCollectionTest('Product');
        $this->runItemTest('Product');
        $this->assertMatchesResourceCollectionJsonSchema(Product::class);

    }

    public function testBrand(): void
    {
        $this->runCollectionTest('Brand');
        $this->runItemTest('Brand');
        $this->assertMatchesResourceCollectionJsonSchema(Brand::class);
    }

    public function testCategory(): void
    {
        $this->runCollectionTest('Category');
        $this->runItemTest('Category');
        $this->assertMatchesResourceCollectionJsonSchema(Category::class);
    }

    public function testColor(): void
    {
        $this->runCollectionTest('Color');
        $this->runItemTest('Color');
        $this->assertMatchesResourceCollectionJsonSchema(Color::class);
    }


    private function runCollectionTest($className): void
    {   
        // get the api uri for the given className
        $uri = $this->getUri($className);

        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        $client = static::createClient();
        $crawler = $client->request('GET', $uri);

        // test if route exists
        //$this->assertResponseIsSuccessful();

       // Asserts that the returned content type is JSON
        $this->assertResponseHeaderSame('Content-Type', 'application/ld+json; charset=utf-8');

        // convert json response to php array 
        // usage of $this->assertJsonContains() is not possible here, because not compatible with PHPUnit v <8.* and PHPUnit v >=8.* is not compatible with Symfony
        $jsonResponse = json_decode($crawler->getContent(), true);

        // test response content
        $this->assertContains('/api/contexts/' . $className, $jsonResponse['@context']);
        $this->assertContains($uri, $jsonResponse['@id']);
        $this->assertContains('hydra:Collection', $jsonResponse['@type']);
        $this->assertContains($className, $jsonResponse['hydra:member'][0]['@type']);

        // test pagination
        if($jsonResponse['hydra:totalItems'] > 5) {
            $this->assertContains($uri, $jsonResponse['hydra:view']['@id']);
            $this->assertContains($uri . '?page=1', $jsonResponse['hydra:view']['hydra:first']);
            $this->assertContains($uri . '?page=2', $jsonResponse['hydra:view']['hydra:next']);
        }

    }

    private function runItemTest($className): void
    {

        $itemId = $this->getFirstItemId($className);

        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        $client = static::createClient();
        $crawler = $client->request('GET', $itemId);

        // test if route exists
        $this->assertResponseIsSuccessful();

       // Asserts that the returned content type is JSON
        $this->assertResponseHeaderSame('Content-Type', 'application/ld+json; charset=utf-8');

        // convert json response to php array 
        // usage of $this->assertJsonContains() is not possible here, because not compatible with PHPUnit v <8.* and PHPUnit v >=8.* is not compatible with Symfony
        $jsonResponse = json_decode($crawler->getContent(), true);

        // test response content
        $this->assertContains('/api/contexts/' . $className, $jsonResponse['@context']);
        $this->assertContains($itemId, $jsonResponse['@id']);
        $this->assertContains($className, $jsonResponse['@type']);

    }

    private function getFirstItemId($className)
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $this->getUri($className));
        $jsonResponse = json_decode($crawler->getContent(), true);
        return $jsonResponse['hydra:member'][0]['@id'];        
    }

    private function getUri($className)
    {
        $targetName = preg_replace("#ry$#", "rie", $className);
        return 'api/' . strtolower($targetName) . 's';
    }
   

}