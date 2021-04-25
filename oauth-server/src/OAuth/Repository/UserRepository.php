<?php

declare(strict_types=1);

namespace App\OAuth\Repository;

use App\Repository\UserRepository as UserModelRepository;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    private UserModelRepository $users;

    public function __construct(UserModelRepository $users)
    {
        $this->users = $users;
    }

    public function getUserEntityByUserCredentials($username, $password, $grantType, ClientEntityInterface $clientEntity)
    {
        $user = $this->users->getByEmail($username);
        if (!$user) {
            return null;
        }

        if (!password_verify($password, $user->getPassword())) {
            return null;
        }

        return $user;
    }
}
