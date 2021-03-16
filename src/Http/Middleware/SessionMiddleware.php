<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class SessionMiddleware implements MiddlewareInterface
{
    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $sessionIdHeader = $request->getHeader('Session-Id');
        if ($sessionIdHeader) {
            $this->session->setId($sessionIdHeader[0]);
        }
        $this->session->start();

        $response = $handler->handle($request);
        $response = $response->withHeader('Session-Id', session_id());

        return $response;
    }
}
