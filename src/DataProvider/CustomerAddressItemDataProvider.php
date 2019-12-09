<?php

namespace App\DataProvider;

use App\Entity\User;
use App\Entity\CustomerAddress;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * API Platform extension
 */
final class CustomerAddressItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    protected $entityManager;
    protected $tokenStorage;

    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage)
    {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Test if operation is supported
     *
     * @param string $resourceClass
     * @param string $operationName
     * @param array $context
     * @return boolean
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return CustomerAddress::class === $resourceClass;
    }

    /**
     * Find the customer address by the given id
     * Only possible if the user connected by auth token owns the customer
     *
     * @param string $resourceClass
     * @param [type] $id
     * @param string $operationName
     * @param array $context
     * @return CustomerAddress|null
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?CustomerAddress
    {

        // get the customer address data with the requested id
        $customerAddress = $this->entityManager
            ->getRepository(CustomerAddress::class)
            ->findOneBy(['id' => $id]);

        // if address found
        if($customerAddress) {

            // get the user that owns the customer of the address
            $customer = $customerAddress->getCustomer();
            $user = $customer->getUser();

            // get the current authenticated user
            $token = $this->tokenStorage->getToken();
            $currentUser = $token->getUser();

            // if the two users are the same, return the resource
            if($currentUser === $user) {
                return $customerAddress;
            }

        }
            
        return null;

    }

}
