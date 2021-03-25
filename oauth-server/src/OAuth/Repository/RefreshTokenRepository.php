<?php

declare(strict_types=1);

namespace App\OAuth\Repository;

use App\OAuth\Model\RefreshTokenEntity;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    public function getNewRefreshToken()
    {
        return new RefreshTokenEntity();
    }

    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
    {
        // TODO: Implement persistNewRefreshToken() method.
    }

    public function revokeRefreshToken($tokenId)
    {
        // TODO: Implement revokeRefreshToken() method
    }

    public function isRefreshTokenRevoked($tokenId)
    {
        return false;
    }
}
