<?php

namespace App\DataProvider;

use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class CustomerItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    protected $entityManager;
    protected $tokenStorage;

    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage)
    {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Customer::class === $resourceClass;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?Customer
    {
        $token = $this->tokenStorage->getToken();
        $user = $token->getUser();

        $customer = $this->entityManager
            ->getRepository(Customer::class)
            ->findOneBy(['id' => $id, 'user' => $user]);
            
        if($customer) {
            return $customer;
        }
        return null;
    }
}
