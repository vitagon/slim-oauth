<?php

declare(strict_types=1);

namespace App\OAuth\Trait;

use League\OAuth2\Server\Entities\ScopeEntityInterface;

trait FormatScopesForStorage
{
    /**
     * @param ScopeEntityInterface[] $scopes
     * @return string
     */
    public function formatScopesForStorage(array $scopes): string
    {
        return json_encode($this->scopesToArray($scopes));
    }

    /**
     * @param ScopeEntityInterface[] $scopes
     * @return array
     */
    public function scopesToArray(array $scopes): array
    {
        return array_map(fn ($scope) => $scope->getIdentifier(), $scopes);
    }
}
