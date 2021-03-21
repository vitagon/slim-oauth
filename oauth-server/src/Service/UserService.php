<?php

declare(strict_types=1);

namespace App\Service;

use JetBrains\PhpStorm\Pure;

class UserService
{
    private array $users = [];

    public function __construct()
    {
        $this->users = [
            [
                'id' => 1,
                'name' => 'Vitalii Goncharov',
                'email' => 'www.devvit@gmail.com',
                'password' => '$2y$10$cpsmp08CpKYcRurujGxgjOwDMxJcrmNknlNNlf0oameSJ3Vnqk/d.'
            ]
        ];
    }

    #[Pure]
    public function getUser(int $id): ?array
    {
        $userKey = array_search($id, array_column($this->users, 'id'));
        return $userKey !== false ? $this->users[$userKey] : null;
    }

    public function getUserByEmail(string $email): ?array
    {
        $userKey = array_search($email, array_column($this->users, 'email'));
        return $userKey !== false ? $this->users[$userKey] : null;
    }
}
