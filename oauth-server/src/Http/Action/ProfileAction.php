<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\Http\Kernel\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ProfileAction implements RequestHandlerInterface
{
    private LoggerInterface $logger;
    private SessionInterface $session;

    public function __construct(LoggerInterface $logger, SessionInterface $session)
    {
        $this->logger = $logger;
        $this->session = $session;
    }

    public function handle(Request $request): Response
    {
        $user = $this->session->get('user');

        return new JsonResponse($user);
    }
}
