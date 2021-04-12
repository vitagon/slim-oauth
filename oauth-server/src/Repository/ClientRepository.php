<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Client;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class ClientRepository
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param string $id
     * @return Client|null
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function getById(string $id): ?Client
    {
        $query = $this->em->createQueryBuilder()
            ->select('c')
            ->from(Client::class, 'c')
            ->where("c.id = :id")
            ->setParameter('id', (int)$id)
            ->getQuery();

        return $query->getSingleResult();
    }
}
