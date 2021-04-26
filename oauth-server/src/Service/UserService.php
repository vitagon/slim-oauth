<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\User;
use App\Repository\UserRepository;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getByEmail(string $email): ?User
    {
        return $this->userRepository->getByEmail($email);
    }

    public function getById(int $id): ?User
    {
        return $this->userRepository->getById($id);
    }
}
