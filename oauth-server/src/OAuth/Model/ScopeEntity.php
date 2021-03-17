<?php

declare(strict_types=1);

namespace App\OAuth\Model;

use League\OAuth2\Server\Entities\ScopeEntityInterface;

class ScopeEntity implements ScopeEntityInterface
{
    public function getIdentifier()
    {
        return 212;
    }

    public function jsonSerialize()
    {
        return 'read';
    }
}
