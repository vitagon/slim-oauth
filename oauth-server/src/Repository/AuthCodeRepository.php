<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\AuthCode;
use App\Model\Client;
use App\Model\User;
use App\OAuth\Dto\AuthCodeDto;
use DateInterval;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

class AuthCodeRepository
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function save(AuthCodeDto $dto): void
    {
        /** @var Client $client */
        $client = $this->em->getReference(Client::class, $dto->clientId);

        /** @var User $user */
        $user = $this->em->getReference(User::class, $dto->userId);

        $expiresAt = (new DateTimeImmutable())->add(new DateInterval('PT60S'));
        $authCode = AuthCode::create($dto->id, $client, $user, $dto->scopes, $expiresAt);
        $this->em->persist($authCode);
        $this->em->flush();
    }

    public function update(AuthCode $authCode): void
    {
        $this->em->persist($authCode);
        $this->em->flush();
    }

    /**
     * @param string $id
     * @return Client|null
     * @throws NonUniqueResultException
     */
    public function getById(string $id): ?AuthCode
    {
        $query = $this->em->createQueryBuilder()
            ->select('a')
            ->from(AuthCode::class, 'a')
            ->where('a.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        return $query->getOneOrNullResult();
    }
}
