<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class SessionMiddleware
{
    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (isset($_COOKIE['PHPSESSID'])) {
            $this->session->setId($_COOKIE['PHPSESSID']);
        }

        $this->session->start();

        if (!isset($_COOKIE['PHPSESSID'])) {
            $domain = sprintf('.%s', parse_url(getenv('APP_URL'), PHP_URL_HOST));
            setcookie('PHPSESSID', $this->session->getId(), 0, '/', $domain);
        }

        return $handler->handle($request);
    }
}
