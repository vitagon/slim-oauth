<?php

declare(strict_types=1);

namespace App\OAuth\Core\Repository;

use App\OAuth\Core\Model\ScopeEntity;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;

class ScopeRepository implements ScopeRepositoryInterface
{
    public function getScopeEntityByIdentifier($identifier): ?ScopeEntityInterface
    {
        return new ScopeEntity();
    }

    public function finalizeScopes(
        array $scopes,
        $grantType,
        ClientEntityInterface $clientEntity,
        $userIdentifier = null
    ) {
        return $scopes;
    }
}
