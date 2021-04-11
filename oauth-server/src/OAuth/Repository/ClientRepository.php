<?php

declare(strict_types=1);

namespace App\OAuth\Repository;

use App\OAuth\Model\ClientEntity;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use App\Repository\ClientRepository as AppClientRepository;

class ClientRepository implements ClientRepositoryInterface
{
    private AppClientRepository $appClientRepository;

    public function __construct(AppClientRepository $appClientRepository)
    {
        $this->appClientRepository = $appClientRepository;
    }

    public function getClientEntity($clientIdentifier): ?ClientEntityInterface
    {
        $client = $this->appClientRepository->getById($clientIdentifier);

        if (!$client) return null;

        return new ClientEntity(
            (string)$client->id,
            $client->name,
            $client->redirect,
            $client->confidential()
        );
    }

    public function validateClient($clientIdentifier, $clientSecret, $grantType)
    {
        return true;
    }
}
