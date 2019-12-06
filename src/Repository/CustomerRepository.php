<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    /**
     * @return Customer[] Returns an array of Customer objects
     */    
    public function findFirstIdBy($user)
    {
        $result = $this->createQueryBuilder('c')
            ->andWhere('c.user = :user')
            ->setParameter('user', $user)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
        if(count($result) > 0) {
            return $result[0]->getId();
        }
        return 0;
    }

    /**
     * @return Customer[] Returns an array of Customer objects
     */    
    public function findIdByUser($user, $maxResults)
    {
        $result = $this->createQueryBuilder('c')
        ->andWhere('c.user = :user')
        ->setParameter('user', $user)
        ->orderBy('c.id', 'ASC')
        ->setMaxResults($maxResults)
        ->getQuery()
        ->getResult()
    ;

    $customersId = array();
    if(count($result) > 0) {
        foreach($result as $value) {
            $customersId[] = $value->getId();
        }
    }
    return $customersId;
}

}
