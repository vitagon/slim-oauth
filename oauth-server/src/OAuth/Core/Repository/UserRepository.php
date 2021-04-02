<?php

declare(strict_types=1);

namespace App\OAuth\Core\Repository;

use App\OAuth\Core\Model\UserEntity;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function getUserEntityByUserCredentials($username, $password, $grantType, ClientEntityInterface $clientEntity)
    {
        if ($username === 'admin' && $password === '123123') {
            return new UserEntity();
        }

        return;
    }
}