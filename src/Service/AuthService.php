<?php

declare(strict_types=1);

namespace App\Service;

use Carbon\Carbon;
use DateTime;
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

    public function getUser(array $credentials): ?array
    {
        if (!isset($credentials['login']) || !isset($credentials['password'])) {
            throw new Exception('Login or password not found in credentials');
        }

        $user = $this->userService->getUserByEmail($credentials['login']);
        if (!$user || !password_verify($credentials['password'], $user['password'])) {
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
