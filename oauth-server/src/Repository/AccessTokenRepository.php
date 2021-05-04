<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\AccessToken;
use App\Model\AuthCode;
use App\Model\Client;
use App\Model\User;
use App\OAuth\Dto\AccessTokenDto;
use App\OAuth\Dto\AuthCodeDto;
use DateInterval;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

class AccessTokenRepository
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function save(AccessTokenDto $dto): void
    {
        /** @var Client $client */
        $client = $this->em->getReference(Client::class, $dto->clientId);

        /** @var User $user */
        $user = $this->em->getReference(User::class, $dto->userId);

        $expiresAt = (new DateTimeImmutable())->add(new DateInterval('PT60S'));
        $createdAt = new DateTimeImmutable();
        $updatedAt = new DateTime();
        $accessToken = AccessToken::create($dto->id, $client, $user, $dto->scopes, $expiresAt, $createdAt, $updatedAt);
        $this->em->persist($accessToken);
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
    public function getById(string $id): ?AccessToken
    {
        $query = $this->em->createQueryBuilder()
            ->select('a')
            ->from(AccessToken::class, 'a')
            ->where('a.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        return $query->getOneOrNullResult();
    }
}
