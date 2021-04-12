<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Scope;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class ScopeRepository
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param int $id
     * @return Scope|null
     * @throws NonUniqueResultException
     */
    public function getById(int $id): ?Scope
    {
        $query = $this->em->createQueryBuilder()
            ->select('s')
            ->from(Scope::class, 's')
            ->where('s.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    /**
     * @param string $name
     * @return Scope|null
     * @throws NonUniqueResultException
     */
    public function getByName(string $name): ?Scope
    {
        $query = $this->em->createQueryBuilder()
            ->select('s')
            ->from(Scope::class, 's')
            ->where("s.name = :name")
            ->setParameter('name', $name)
            ->getQuery();

        return $query->getOneOrNullResult();
    }
}
