<?php

declare(strict_types=1);

namespace App\OAuth\Repository;

use App\OAuth\Model\ClientEntity;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

class ClientRepository implements ClientRepositoryInterface
{
    public function getClientEntity($clientIdentifier): ClientEntityInterface
    {
        return new ClientEntity(
            '899',
            'test',
            'http://test.loc/callback',
            true
        );
    }

    public function validateClient($clientIdentifier, $clientSecret, $grantType)
    {
        return true;
    }
}
