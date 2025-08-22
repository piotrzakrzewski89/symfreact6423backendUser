<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getAllUsersActive(): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.isDeleted = false')
            ->getQuery()
            ->getResult();
    }

    public function getAllUsersDeleted(): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.isDeleted = true')
            ->getQuery()
            ->getResult();
    }

    public function getUser(int $id): User
    {
        return $this->createQueryBuilder('u')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
    }
}
