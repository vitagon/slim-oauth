<?php

declare(strict_types=1);

namespace App\OAuth\Model;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\ClientTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

class ClientEntity implements ClientEntityInterface
{
    use ClientTrait;
    use EntityTrait;

    /**
     * ClientEntity constructor.
     * @param string $identifier
     * @param string $name
     * @param string|string[] $redirectUri
     * @param bool $isConfidential
     */
    public function __construct(
        string $identifier,
        string $name,
        $redirectUri,
        bool $isConfidential
    ) {
        $this->identifier = $identifier;
        $this->name = $name;
        $this->redirectUri = $redirectUri;
        $this->isConfidential = $isConfidential;
    }
}
