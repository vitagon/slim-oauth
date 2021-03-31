<?php

declare(strict_types=1);

namespace App\OAuth\Bridge\Model;

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;

class AccessTokenEntity implements AccessTokenEntityInterface
{
    use AccessTokenTrait;
    use EntityTrait;
    use TokenEntityTrait;

    public function __construct(
        array $scopes,
        ClientEntityInterface $client,
        string $userIdentifier
    ) {
        $this->scopes = $scopes;
        $this->client = $client;
        $this->userIdentifier = $userIdentifier;
    }
}
