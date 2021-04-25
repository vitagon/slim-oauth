<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Client;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;

class ClientRepository
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param int $id
     * @return Client
     * @throws ORMException
     */
    public function getReference(int $id): Client
    {
        /** @var Client $clientRef */
        $clientRef = $this->em->getReference(Client::class, $id);
        return $clientRef;
    }

    /**
     * @param string $id
     * @return Client|null
     * @throws NonUniqueResultException
     */
    public function getById(string $id): ?Client
    {
        $query = $this->em->createQueryBuilder()
            ->select('c')
            ->from(Client::class, 'c')
            ->where('c.id = :id')
            ->setParameter('id', (int)$id)
            ->getQuery();

        return $query->getOneOrNullResult();
    }
}
