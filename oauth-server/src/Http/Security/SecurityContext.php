<?php

declare(strict_types=1);

namespace App\Http\Security;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SecurityContext
{
    private SessionInterface $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function getUser()
    {
        return $this->session->get('user');
    }

    public function logout()
    {
        $this->session->remove('user');
    }
}
