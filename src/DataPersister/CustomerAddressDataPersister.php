<?php

namespace App\DataPersister;

use App\Entity\Customer;
use App\Entity\CustomerAddress;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * API Plaform extension
 */
class CustomerAddressDataPersister implements DataPersisterInterface
{
    private $entityManager;
    private $tokenStorage;

    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage)
    {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Operation supported for given data
     *
     * @param CustomerAddress $data
     * @return boolean
     */
    public function supports($data): bool
    {
        return $data instanceof CustomerAddress;
    }

    /**
     * POST or PUT customer address
     *
     * @param CustomerAddress $customerAddress
     * @return void
     */
    public function persist($customerAddress)
    {

        // get the current authenticated user
        $token = $this->tokenStorage->getToken();
        $currentUser = $token->getUser();

        // get the customer by the given customer value and the current user
        $customer = $this->entityManager
            ->getRepository(Customer::class)
            ->findOneBy(['id' => $customerAddress->getCustomerId(), 'user' => $currentUser]);

        // if the customer don't exists or is not owned by the current user, throw 404 exception
        if(!$customer) {
            throw new NotFoundHttpException('The customer ' . $customerAddress->getCustomerId() . ' does not exist.');
        }

        // if the customer exists and is owned by the current user, set default values and persist
        $customerAddress->setCustomer($customer);
        if($customerAddress->isDefault() == "") {
            $customerAddress->setDefault(false);
        }
        $this->entityManager->persist($customerAddress);
        $this->entityManager->flush();

    }
    
    /**
     * DELETE customer address
     *
     * @param CustomerAddress $customerAddress
     * @return void
     */
    public function remove($customerAddress)
    {
        $this->entityManager->remove($customerAddress);
        $this->entityManager->flush();
    }

}
