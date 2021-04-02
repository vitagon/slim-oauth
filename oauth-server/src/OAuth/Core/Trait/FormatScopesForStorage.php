<?php

declare(strict_types=1);

use App\OAuth\Core\Model\ScopeEntity;

trait FormatScopesForStorage
{
    /**
     * FormatScopesForStorage constructor.
     * @param ScopeEntity[] $scopes
     */
    public function formatScopesForStorage(array $scopes): string
    {
        return json_encode($this->scopesToArray($scopes));
    }

    /**
     * @param ScopeEntity[] $scopes
     * @return array
     */
    public function scopesToArray(array $scopes): array
    {
        return array_map(fn ($scope) => $scope->getIdentifier(), $scopes);
    }
}
