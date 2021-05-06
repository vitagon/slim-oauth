<?php

declare(strict_types=1);

namespace App\OAuth\Repository;

use App\OAuth\Model\ScopeEntity;
use App\Repository\ScopeRepository as ScopeModelRepository;
use Doctrine\ORM\NonUniqueResultException;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;

class ScopeRepository implements ScopeRepositoryInterface
{
    private ScopeModelRepository $scopes;

    public function __construct(ScopeModelRepository $scopes)
    {
        $this->scopes = $scopes;
    }

    /**
     * @param string $identifier
     * @throws NonUniqueResultException
     */
    public function getScopeEntityByIdentifier($identifier): ?ScopeEntity
    {
        $scope = $this->scopes->getById($identifier);
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
