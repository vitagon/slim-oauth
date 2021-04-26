<?php

declare(strict_types=1);

namespace App\Http\Security;

use App\Model\User;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SecurityContext
{
    private SessionInterface $session;
    private UserService $userService;

    public function __construct(SessionInterface $session, UserService $userService)
    {
        $this->session = $session;
        $this->userService = $userService;
    }

    public function getUser(): ?User
    {
        $userId = $this->session->get('auth.user_id');
        if (!$userId) {
            return null;
        }

        return $this->userService->getById($userId);
    }

    public function logout()
    {
        $this->session->remove('auth.user_id');
    }
}
