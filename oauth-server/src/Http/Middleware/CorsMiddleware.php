<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteContext;

class CorsMiddleware
{
    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $routeContext = RouteContext::fromRequest($request);
        $routingResults = $routeContext->getRoutingResults();
        $methods = $routingResults->getAllowedMethods();
        $requestHeaders = $request->getHeaderLine('Access-Control-Request-Headers');

        $response = $handler->handle($request);

        $allowedDomain = getenv('APP_FRONT_DOMAIN');
        if ($allowedDomain) {
            $response = $response->withHeader('Access-Control-Allow-Origin', $allowedDomain);
            $response = $response->withHeader('Access-Control-Allow-Headers', $requestHeaders);
            $response = $response->withHeader('Access-Control-Allow-Methods', implode(',', $methods));
            $response = $response->withHeader('Access-Control-Allow-Credentials','true');
        }

        return $response;
    }
}
