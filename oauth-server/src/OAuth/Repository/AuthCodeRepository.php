<?php

declare(strict_types=1);

namespace App\OAuth\Repository;

use App\OAuth\Model\AuthCodeEntity;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;

class AuthCodeRepository implements AuthCodeRepositoryInterface
{
    public function getNewAuthCode()
    {
        return new AuthCodeEntity();
    }

    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity)
    {
        // TODO: Implement persistNewAuthCode() method.
    }

    public function revokeAuthCode($codeId)
    {
        // TODO: Implement revokeAuthCode() method.
    }

    public function isAuthCodeRevoked($codeId)
    {
        return false; // The auth code has not been revoked
    }
}