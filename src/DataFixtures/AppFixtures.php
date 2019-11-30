<?php

namespace App\DataFixtures;
use App\Entity\Brand;
use App\Entity\Color;
use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\Yaml\Yaml;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{

    /**
     * Load fixtures in a certain order
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $this->loadBrands($manager, 'src/DataFixtures/Data/brand.yaml');
        $this->loadCategories($manager, 'src/DataFixtures/Data/category.yaml');
        $this->loadColors($manager, 'src/DataFixtures/Data/color.yaml');
        $this->loadProducts($manager, 'src/DataFixtures/Data/product.yaml');
        //$this->loadMedias($manager, 'src/DataFixtures/Data/media.yaml');
    }

    /**
     * Load brands from Yaml data file and store into database
     *
     * @param ObjectManager $manager
     * @param string $dataPath
     * @return void
     */
    private function loadBrands(ObjectManager $manager, $dataPath)
    {
        $fixtureData = Yaml::parseFile($dataPath);

        foreach($fixtureData as $brandData) {
            $brand = new Brand();
            $brand->setName($brandData['name']);
            $brand->setLogo($brandData['logo']);
            $manager->persist($brand);
            $this->addReference('[brand] ' . $brandData['name'], $brand);
        }

        $manager->flush();
    }

    /**
     * Load categories from Yaml data file and store into database
     *
     * @param ObjectManager $manager
     * @param string $dataPath
     * @return void
     */
    private function loadCategories(ObjectManager $manager, $dataPath)
    {
        $fixtureData = Yaml::parseFile($dataPath);

        foreach($fixtureData as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $manager->persist($category);
            $this->addReference('[category] ' . $categoryName, $category);
        }

        $manager->flush();
    }

    /**
     * Load colors from Yaml data file and store into database
     *
     * @param ObjectManager $manager
     * @param string $dataPath
     * @return void
     */
    private function loadColors(ObjectManager $manager, $dataPath)
    {
        $fixtureData = Yaml::parseFile($dataPath);

        foreach($fixtureData as $colorData) {
            $color = new Color();
            $color->setName($colorData['name']);
            $color->setHexa($colorData['hexa']);
            $manager->persist($color);
            $this->addReference('[color] ' . $colorData['name'], $color);
        }

        $manager->flush();
    }

    /**
     * Load tricks from Yaml data file and store into database
     *
     * @param ObjectManager $manager
     * @param string $dataPath
     * @return void
     */
    private function loadProducts(ObjectManager $manager, $dataPath)
    {
        $fixtureData = Yaml::parseFile($dataPath);

        foreach($fixtureData as $productData) {
            $product = new Product();
            $product->setBrand($this->getReference('[brand] ' . $productData['brandName']));
            $product->setCategory($this->getReference('[category] ' . $productData['categoryName']));
            $product->setColor($this->getReference('[color] ' . $productData['colorName']));
            $product->setName($productData['name']);
            $product->setReference($productData['reference']);
            $product->setGencode($productData['gencode']);
            $product->setDescription($productData['description']);
            $product->setPublicPrice($productData['public_price']);
            $product->setPrice($productData['price']);
            $product->setStock($productData['stock']);
            $product->setDimensions($productData['dimensions']);
            $product->setWeight($productData['weight']);
            $product->setMemory($productData['memory']);
            $product->setOs($productData['os']);
            $product->setDisplay($productData['display']);
            $product->setBattery($productData['battery']);
            $product->setCamera($productData['camera']);
            $product->setSensors($productData['sensors']);
            $product->setConnectivity($productData['connectivity']);
            $product->setBandQuad($productData['bandQuad']);
            $manager->persist($product);
            $this->addReference('[product] ' . $productData['name'], $product);
        }

        $manager->flush();
    }

    /**
     * Load medias from Yaml data file and store into database
     *
     * @param ObjectManager $manager
     * @param string $dataPath
     * @return void
     */
    private function loadMedias(ObjectManager $manager, $dataPath)
    {
        $fixtureData = Yaml::parseFile($dataPath);

        foreach($fixtureData as $trickData) {
            foreach($trickData['media'] as $url) {
                $media = new Media();
                $media->setTrick($this->getReference('[trick] ' . $trickData['trickName']));
                $media->setUrl($url);
                $manager->persist($media);
            }
        }

        $manager->flush();
    }


}
