<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Exception;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthorizationServerMiddleware implements MiddlewareInterface
{
    private AuthorizationServer $server;

    public function __construct(AuthorizationServer $server)
    {
        $this->server = $server;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = null;
        try {
            $response = $handler->handle($request);
            $response = $this->server->respondToAccessTokenRequest($request, $response);
        } catch (OAuthServerException $exception) {
            return $exception->generateHttpResponse($response);
        } catch (Exception $exception) {
            return (new OAuthServerException($exception->getMessage(), 0, 'unknown_error', 500))
                ->generateHttpResponse($response);
        }

        // Pass the request and response on to the next responder in the chain
        return $response;
    }
}
