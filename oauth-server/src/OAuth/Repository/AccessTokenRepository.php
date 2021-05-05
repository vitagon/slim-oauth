<?php

declare(strict_types=1);

namespace App\OAuth\Repository;

use App\OAuth\Dto\AccessTokenDto;
use App\OAuth\Model\AccessTokenEntity;
use App\OAuth\Trait\FormatScopesForStorage;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use JetBrains\PhpStorm\Pure;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use App\Repository\AccessTokenRepository as AccessTokenModelRepository;

class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    use FormatScopesForStorage;

    private AccessTokenModelRepository $repository;
    private EntityManagerInterface $em;

    public function __construct(AccessTokenModelRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    #[Pure]
    public function getNewToken(
        ClientEntityInterface $clientEntity,
        array $scopes,
        $userIdentifier = null
    ): AccessTokenEntityInterface {
        return new AccessTokenEntity(
            $scopes,
            $clientEntity,
            (string)$userIdentifier
        );
    }

    /**
     * @throws ORMException
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity)
    {
        $this->repository->save(AccessTokenDto::create(
            $accessTokenEntity->getIdentifier(),
            (int)$accessTokenEntity->getUserIdentifier(),
            (int)$accessTokenEntity->getClient()->getIdentifier(),
            $this->formatScopesForStorage($accessTokenEntity->getScopes()),
            false,
            $accessTokenEntity->getExpiryDateTime(),
            new DateTimeImmutable(),
            new DateTime(),
        ));
    }

    /**
     * @throws NonUniqueResultException
     */
    public function revokeAccessToken($tokenId): void
    {
        $accessToken = $this->repository->getById($tokenId);
        if ($accessToken) {
            $accessToken->revoke();
            $this->em->persist($accessToken);
            $this->em->flush();
        }
    }

    /**
     * @throws NonUniqueResultException
     */
    public function isAccessTokenRevoked($tokenId): bool
    {
        $accessToken = $this->repository->getById($tokenId);
        return $accessToken->revoked;
    }
}
