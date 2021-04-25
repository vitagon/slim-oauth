<?php

declare(strict_types=1);

namespace App\OAuth\Repository;

use App\OAuth\Model\ClientEntity;
use App\Repository\ClientRepository as ClientModelRepository;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

class ClientRepository implements ClientRepositoryInterface
{
    private ClientModelRepository $clients;

    public function __construct(ClientModelRepository $clients)
    {
        $this->clients = $clients;
    }

    public function getClientEntity($clientIdentifier): ?ClientEntityInterface
    {
        $client = $this->clients->getById($clientIdentifier);
        if (!$client) {
            return null;
        }

        return new ClientEntity(
            (string)$client->id,
            $client->name,
            $client->redirect,
            $client->confidential()
        );
    }

    public function validateClient($clientIdentifier, $clientSecret, $grantType): bool
    {
        $client = $this->clients->getById($clientIdentifier);
        if (!$client || $client->revoked) {
            return false;
        }

        if ($client->secret !== $clientSecret) {
            return false;
        }

        return true;
    }
}
