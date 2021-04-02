<?php

declare(strict_types=1);

namespace App\OAuth\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use JetBrains\PhpStorm\Pure;
use App\OAuth\Model\Client;

class ClientRepository
{
    private Connection $connection;
    private EntityManagerInterface $em;

    #[Pure]
    public function __construct(EntityManager $em)
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
        $query = $this->em->createQuery('select c from Client where id = :id');
        $query->setParameter('id', $id);

        return $query->getSingleResult();
    }
}
