<?php

declare(strict_types=1);

namespace App\Http\Action;

use Exception;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Stream;

class AccessTokenAction
{
    private AuthorizationServer $server;

    public function __construct(AuthorizationServer $server)
    {
        $this->server = $server;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            // Try to respond to the request
            return $this->server->respondToAccessTokenRequest($request, $response);
        } catch (OAuthServerException $exception) {
            // All instances of OAuthServerException can be formatted into a HTTP response
            return $exception->generateHttpResponse($response);
        } catch (Exception $exception) {
            // Unknown exception
            $body = new Stream(fopen('php://temp', 'r+'));
            $body->write($exception->getMessage());
            return $response->withStatus(500)->withBody($body);
        }
    }
}
