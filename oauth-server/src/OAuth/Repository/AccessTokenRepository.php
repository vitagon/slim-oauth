<?php

declare(strict_types=1);

namespace App\OAuth\Repository;

use App\OAuth\Dto\AccessTokenDto;
use App\OAuth\Model\AccessTokenEntity;
use App\OAuth\Trait\FormatScopesForStorage;
use DateTime;
use DateTimeImmutable;
use JetBrains\PhpStorm\Pure;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use App\Repository\AccessTokenRepository as AccessTokenModelRepository;

class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    use FormatScopesForStorage;

    private AccessTokenModelRepository $repository;

    public function __construct(AccessTokenModelRepository $repository)
    {
        $this->repository = $repository;
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

    public function revokeAccessToken($tokenId)
    {
        // TODO: Implement revokeAccessToken() method.
    }

    public function isAccessTokenRevoked($tokenId)
    {

    }
}
