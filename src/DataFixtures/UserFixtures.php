<?php

namespace App\DataFixtures;
use Faker;
use App\Entity\User;
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


}
