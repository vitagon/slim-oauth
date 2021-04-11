<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use JetBrains\PhpStorm\Pure;
use App\Model\Client;

class ClientRepository
{
    private Connection $connection;
    private EntityManagerInterface $em;

    #[Pure]
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->connection = $em->getConnection();
    }

//    public function getById(string $id): array
//    {
//        $query = $this->connection->createQueryBuilder();
//
//        return $query
//            ->select('c')
//            ->from('oauth_clients', 'c')
//            ->where('c.id = :id')
//            ->setParameter('id', $id)
//            ->fetchAllAssociative();
//    }

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
