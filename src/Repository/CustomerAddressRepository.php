<?php

namespace App\Repository;

use App\Entity\CustomerAddress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CustomerAddress|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomerAddress|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomerAddress[]    findAll()
 * @method CustomerAddress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerAddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerAddress::class);
    }

    /**
     * Returns the id of the first item found with the given user
     *
     * @param User $user
     * @return int
     */
    public function findFirstIdBy($user)
    {
        $result = $this->createQueryBuilder('ca')
        ->andWhere('c.user = :user')
        ->setParameter('user', $user)
        ->leftJoin('ca.customer', 'c')
        ->orderBy('ca.id', 'ASC')
        ->getQuery()
        ->getResult();
        if(count($result) > 0) {
            return $result[0]->getId();
        }
        return 0;
    }

}
