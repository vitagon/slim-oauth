<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\Http\Kernel\JsonResponse;
use App\Service\UserService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

class ProfileAction implements RequestHandlerInterface
{
    private LoggerInterface $logger;
    private UserService $userService;

    public function __construct(LoggerInterface $logger, UserService $userService)
    {
        $this->logger = $logger;
        $this->userService = $userService;
    }

    public function handle(Request $request): Response
    {
        $token = $request->getAttribute('token');
        $user = $this->userService->getUser($token->uid);

        return new JsonResponse(['user' => $user]);
    }
}
