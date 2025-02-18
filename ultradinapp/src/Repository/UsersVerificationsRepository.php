<?php

namespace App\Repository;

use App\Entity\UsersVerifications;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\User;

/**
 * @extends ServiceEntityRepository<UsersVerifications>
 */
class UsersVerificationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsersVerifications::class);
    }

    public function getUserVerifications(User $user): ?UsersVerifications
    {
        return $this->findOneBy(['user' => $user]);
    }
}
