<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\Http\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use stdClass;
use Symfony\Component\HttpFoundation\Session\Session;

class HomeAction implements RequestHandlerInterface
{
    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function handle(Request $request): Response
    {
        $this->session->set('msg', 'Updated msg 111');
//        $sessionIdHeader = $request->getHeader('Session-Id');
//        if ($sessionIdHeader) {
//            $sessionId = $sessionIdHeader[0];
//            session_id($sessionId);
//            session_start(['use_cookies' => 0]);
//        } else {
//            session_start(['use_cookies' => 0]);
//            $sessionId = session_id();
//        }
//
//        $_SESSION['ttt'] = 'Updated message';

//        return new JsonResponse(new stdClass(), 200, ['Session-Id' => $sessionId]);
        return new JsonResponse(new stdClass());
    }
}
