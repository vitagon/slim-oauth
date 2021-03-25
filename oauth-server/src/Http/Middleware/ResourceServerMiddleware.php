<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Kernel\JsonResponse;
use Exception;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ResourceServerMiddleware implements MiddlewareInterface
{
    private ResourceServer $server;

    public function __construct(ResourceServer $server)
    {
        $this->server = $server;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $request = $this->server->validateAuthenticatedRequest($request);
        } catch (OAuthServerException $exception) {
            return $exception->generateHttpResponse(new JsonResponse([]));
        } catch (Exception $exception) {
            return (new OAuthServerException(
                $exception->getMessage(),
                0,
                'unknown_error',
                500
            ))->generateHttpResponse(new JsonResponse([]));
        }

        return $handler->handle($request);
    }
}
