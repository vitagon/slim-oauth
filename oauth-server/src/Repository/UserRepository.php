<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Client;
use App\Model\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

class UserRepository
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getReference(int $id): User
    {
        /** @var User $userRef */
        $userRef = $this->em->getReference(User::class, $id);
        return $userRef;
    }

    /**
     * @param string $id
     * @return Client|null
     * @throws NonUniqueResultException
     */
    public function getById(string $id): ?User
    {
        $query = $this->em->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.id = :id')
            ->setParameter('id', (int)$id)
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    /**
     * @param string $email
     * @return Client|null
     * @throws NonUniqueResultException
     */
    public function getByEmail(string $email): ?User
    {
        $query = $this->em->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery();

        return $query->getOneOrNullResult();
    }
}
