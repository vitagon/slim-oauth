<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Scope;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

class ScopeRepository
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param string $id Scope name
     * @return Scope|null
     * @throws NonUniqueResultException
     */
    public function getById(string $id): ?Scope
    {
        $query = $this->em->createQueryBuilder()
            ->select('s')
            ->from(Scope::class, 's')
            ->where('s.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        return $query->getOneOrNullResult();
    }
}
