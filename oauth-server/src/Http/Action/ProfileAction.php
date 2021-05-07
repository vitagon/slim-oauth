<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\Http\Kernel\JsonResponse;
use App\Http\Security\SecurityContext;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

class ProfileAction
{
    private LoggerInterface $logger;
    private SecurityContext $securityContext;

    public function __construct(LoggerInterface $logger, SecurityContext $securityContext)
    {
        $this->logger = $logger;
        $this->securityContext = $securityContext;
    }

    /**
     * @throws JsonException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $user = $this->securityContext->getUser();

        return new JsonResponse($user);
    }
}
