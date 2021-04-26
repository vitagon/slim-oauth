<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\User;
use Carbon\Carbon;
use Exception;
use Firebase\JWT\JWT;

class AuthService
{
    private UserService $userService;
    private string $jwtSecret;

    public function __construct(UserService $userService, string $jwtSecret)
    {
        $this->userService = $userService;
        $this->jwtSecret = $jwtSecret;
    }

    public function getUser(array $credentials): ?User
    {
        if (!isset($credentials['login']) || !isset($credentials['password'])) {
            throw new Exception('Login or password not found in credentials');
        }

        $user = $this->userService->getByEmail($credentials['login']);
        if (!$user || !password_verify($credentials['password'], $user->getPassword())) {
            return null;
        }

        return $user;
    }

    public function createToken(array $user): string
    {
        return JWT::encode([
            'uid' => $user['id'],
            'name' => $user['name'],
            'iss' => 'http://ourcompany.com',
            'iat' => Carbon::now()->getTimestamp(),
        ], $this->jwtSecret);
    }
}
