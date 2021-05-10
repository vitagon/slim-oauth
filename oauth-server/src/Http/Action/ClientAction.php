<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\Http\Kernel\JsonResponse;
use App\Http\Security\OAuthSecurityContext;
use OAuthException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ClientAction
{
    /**
     * @throws OAuthException
     */
    public function __invoke(ServerRequestInterface $request, OAuthSecurityContext $securityContext): ResponseInterface
    {
        return new JsonResponse($securityContext->getUser($request));
    }
}
