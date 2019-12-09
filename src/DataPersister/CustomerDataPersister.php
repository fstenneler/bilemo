<?php

namespace App\DataPersister;

use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * API Platfom extention
 */
class CustomerDataPersister implements DataPersisterInterface
{
    private $entityManager;
    private $tokenStorage;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Operation supported for given data
     *
     * @param Customer $data
     * @return boolean
     */
    public function supports($data): bool
    {
        return $data instanceof Customer;
    }

    /**
     * POST or PUT customer
     * The user is given by the API auth token
     *
     * @param Customer $customer
     * @return void
     */
    public function persist($customer)
    {

        // get the current authenticated user
        $token = $this->tokenStorage->getToken();
        $user = $token->getUser();

        // if the user is defined, persist the customer
        if($user) {
            $customer->setUser($user);
            $customer->setPassword($this->passwordEncoder->encodePassword($customer, $customer->getPassword()));
            $customer->setCreatedAt(new \DateTime);
            $this->entityManager->persist($customer);
            $this->entityManager->flush();
        }

    }

    /**
     * DELETE customer
     *
     * @param Customer $customer
     * @return void
     */
    public function remove($customer)
    {
        $this->entityManager->remove($customer);
        $this->entityManager->flush();
    }

}
