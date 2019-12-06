<?php

namespace App\DataPersister;

use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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

    public function supports($data): bool
    {
        return $data instanceof Customer;
    }

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

    public function remove($customer)
    {
        $this->entityManager->remove($customer);
        $this->entityManager->flush();
    }

}
