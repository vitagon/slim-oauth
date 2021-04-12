<?php

declare(strict_types=1);

namespace App\OAuth\Repository;

use App\OAuth\Model\ScopeEntity;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use App\Repository\ScopeRepository as AppScopeRepository;

class ScopeRepository implements ScopeRepositoryInterface
{
    private AppScopeRepository $appScopeRepository;

    public function __construct(AppScopeRepository $appScopeRepository)
    {
        $this->appScopeRepository = $appScopeRepository;
    }

    public function getScopeEntityByIdentifier($identifier)
    {
        $scope = $this->appScopeRepository->getByName((string)$identifier);
        if (!$scope) {
            return null;
        }

        return new ScopeEntity($scope);
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
