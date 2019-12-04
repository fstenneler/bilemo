<?php

namespace App\DataFixtures;
use Faker;
use App\Entity\User;
use App\Entity\Customer;
use App\Entity\CustomerAddress;
use Symfony\Component\Yaml\Yaml;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Load fixtures in a certain order
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
        $this->loadCustomers($manager);
        $this->loadCustomerAddresses($manager);
    }

    /**
     * Load users from Yaml data file and store into database
     *
     * @param ObjectManager $manager
     * @param string $dataPath
     * @return void
     */
    private function loadUsers(ObjectManager $manager)
    {
        
        $user = new User();
        $user->setUsername('admin');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'admin')); 
        $user->setRoles(['ROLE_ADMIN']);       
        $user->setCompany('Bilemo');
        $user->setManagerName('Fabien Stenneler');
        $user->setManagerEmail('fabien@orlinstreet.rocks');
        $user->setManagerPhone('+33612345678');
        $manager->persist($user);
        
        $faker = Faker\Factory::create('en_US');

        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setUsername($faker->userName);
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'user')); 
            $user->setRoles(['ROLE_USER']);       
            $user->setCompany($faker->company);
            $user->setManagerName($faker->name);
            $user->setManagerEmail($faker->companyEmail);
            $user->setManagerPhone($faker->phoneNumber);
            $manager->persist($user);
            $this->addReference('[user] ' . $i, $user);
        }

        $manager->flush();

    }

    /**
     * Load customers from Yaml data file and store into database
     *
     * @param ObjectManager $manager
     * @param string $dataPath
     * @return void
     */
    private function loadCustomers(ObjectManager $manager)
    {
        
        $faker = Faker\Factory::create('en_US');

        for ($i = 0; $i < 30; $i++) {

            $userId = rand(0, 4);
            $isBirthdayDefined = rand(0, 1);

            $customer = new Customer();
            $customer->setEmail($faker->email);
            $customer->setPassword($this->passwordEncoder->encodePassword($customer, $faker->password));
            if($isBirthdayDefined === 1) {
                $customer->setBirthday($faker->dateTimeBetween($startDate = '-80 years', $endDate = '-18 years', $timezone = null));
            }
            $customer->setPhone($faker->phoneNumber);
            $customer->setCreatedAt($faker->dateTimeThisYear($max = 'now', $timezone = null));
            $customer->setUser($this->getReference('[user] ' . $userId));
            $manager->persist($customer);
           $this->addReference('[customer] ' . $i, $customer);

        }

        $manager->flush();

    }

    /**
     * Load customer addresses from Yaml data file and store into database
     *
     * @param ObjectManager $manager
     * @param string $dataPath
     * @return void
     */
    private function loadCustomerAddresses(ObjectManager $manager)
    {
        
        $faker = Faker\Factory::create('en_US');

        for ($iCustomer = 0; $iCustomer < 10; $iCustomer++) {

            $numberOfAddress = rand(1, 5);
            $defaultAddress = rand(1, $numberOfAddress);

            for($iAddress = 1; $iAddress <= $numberOfAddress; $iAddress++) {
    
                $address = new CustomerAddress();

                $isBusiness = rand(0,1);
                $hasSecondaryAddress = rand(0,1);
                
                $address->setFirstName($faker->firstName());
                $address->setLastName($faker->lastName());
                if($isBusiness === 1) {
                    $address->setBusiness($faker->company);
                }
                $address->setAddressLine1($faker->streetAddress);
                if($hasSecondaryAddress === 1) {
                    $address->setAddressLine2($faker->secondaryAddress);
                }
                $address->setPostalCode($faker->postcode);
                $address->setCity($faker->city);
                $address->setCountry($faker->country);
                if($iAddress === $defaultAddress) {
                    $address->setDefault(true);
                } else {
                    $address->setDefault(false);
                }
                $address->setCustomer($this->getReference('[customer] ' . $iCustomer));
                $manager->persist($address);

            }

        }

        $manager->flush();

    }


}
