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
        if (isset($_COOKIE['PHPSESSID'])) {
            $this->session->setId($_COOKIE['PHPSESSID']);
        }

        $this->session->start();

        $response = $handler->handle($request);

        if (!isset($_COOKIE['PHPSESSID'])) {
            $domain = sprintf('.%s', parse_url(getenv('APP_URL'), PHP_URL_HOST));
            setcookie('PHPSESSID', $this->session->getId(), 0, '/', $domain);
        }

        return $response;
    }
}
