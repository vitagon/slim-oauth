<?php

declare(strict_types=1);

namespace App\OAuth\Model;

use App\Model\Scope;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

class ScopeEntity implements ScopeEntityInterface
{
    use EntityTrait;

    public function __construct(string $id)
    {
        $this->setIdentifier($id);
    }

    public function jsonSerialize()
    {
        return $this->getIdentifier();
    }
}
