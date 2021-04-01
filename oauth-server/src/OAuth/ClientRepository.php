<?php

declare(strict_types=1);

namespace App\OAuth;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;

class ClientRepository
{
    private Connection $connection;

    public function __construct(EntityManager $em)
    {
        $this->connection = $em->getConnection();
    }

    public function getById(string $id)
    {
        $query = $this->connection->createQueryBuilder();

        $res = $query
            ->select('c')
            ->from('oauth_clients', 'c')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->fetchAllAssociative();
    }
}
