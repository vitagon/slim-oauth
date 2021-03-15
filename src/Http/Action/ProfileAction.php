<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\Http\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class ProfileAction implements RequestHandlerInterface
{
    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function handle(Request $request): Response
    {

//        $sessionIdHeader = $request->getHeader('Session-Id');
//        if ($sessionIdHeader) {
//            $sessionId = $sessionIdHeader[0];
//            session_id($sessionId);
//            session_start(['use_cookies' => 0]);
//        }
//        return new JsonResponse(['msg' => $_SESSION['ttt']]);
        return new JsonResponse(['msg' => $this->session->get('msg')]);
    }
}
