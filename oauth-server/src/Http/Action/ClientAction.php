<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\Http\Kernel\JsonResponse;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;

class ClientAction
{
    private ResourceServer $resourceServer;

    public function __construct(ResourceServer $resourceServer)
    {
        $this->resourceServer = $resourceServer;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $req = $this->resourceServer->validateAuthenticatedRequest($request);
        } catch (OAuthServerException $e) {
            throw $e;
        }
        return new JsonResponse($req->getAttributes());
    }
}
