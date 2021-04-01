<?php

declare(strict_types=1);

namespace App\OAuth\Bridge\Repository;

use App\OAuth\Bridge\Model\ClientEntity;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

class ClientRepository implements ClientRepositoryInterface
{
    private ClientRepository $clientRepository;

    public function getClientEntity($clientIdentifier): ClientEntityInterface
    {
        return new ClientEntity(
            '899',
            '',
            'http://client.loc/callback',
            true
        );
    }

    public function validateClient($clientIdentifier, $clientSecret, $grantType)
    {
        return true;
    }
}
