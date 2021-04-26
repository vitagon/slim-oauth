<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Security\SecurityContext;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class WebAuthMiddleware implements MiddlewareInterface
{
    private SessionInterface $session;
    private SecurityContext $securityContext;

    public function __construct(SessionInterface $session, SecurityContext $securityContext)
    {
        $this->session = $session;
        $this->securityContext = $securityContext;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = $this->securityContext->getUser();

        $contentType = $request->getHeader('Content-Type')[0];
        if (!$user &&  substr($contentType, 0, 16) === 'application/json') {
            return (new Response())->withStatus(401);
        } elseif (!$user) {
            $this->session->set('auth.last_url', $request->getUri());
            return (new Response())->withHeader('Location', '/login');
        }

        return $handler->handle($request);
    }
}
