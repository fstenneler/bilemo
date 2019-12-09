<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Returns the User object of the first item found with the role given
     *
     * @param string $role A string for the role to find
     * @return User
     */
    public function findFirstBy($role)
    {
        $result = $this->createQueryBuilder('u')
            ->andWhere('u.roles LIKE :role')
            ->setParameter('role', '%' . $role . '%')
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
        return $result[0];
    }

    /**
     * Returns the User object of a user having the given role, excepted the given userId
     *
     * @param [type] $role A string for the role to find
     * @param [type] $userId An integer to identify the user not to find
     * @return User
     */
    public function findAnotherOne($role, $userId)
    {
        $result = $this->createQueryBuilder('u')
            ->andWhere('u.roles LIKE :role')
            ->andWhere('u.id != :id')
            ->setParameter('role', '%' . $role . '%')
            ->setParameter('id', $userId)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
        return $result[0];
    }

}
