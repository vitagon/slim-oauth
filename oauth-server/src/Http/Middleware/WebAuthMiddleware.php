<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class WebAuthMiddleware implements MiddlewareInterface
{
    private SessionInterface $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = $this->session->get('user');
        $this->session->set('auth.last_url', $request->getUri());
        $contentType = $request->getHeader('Content-Type')[0];
        if (!$user &&  substr($contentType, 0, 16) === 'application/json') {
            return (new Response())->withStatus(401);
        } elseif (!$user) {
            return (new Response())->withHeader('Location', '/login');
        }
        return $handler->handle($request);
    }
}
