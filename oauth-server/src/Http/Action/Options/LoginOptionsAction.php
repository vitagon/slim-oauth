<?php

declare(strict_types=1);

namespace App\Http\Action\Options;

use App\Http\Kernel\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;

class LoginOptionsAction implements RequestHandlerInterface
{
    public function handle(Request $request): Response
    {
        return new JsonResponse([], 200, [
            'Access-Control-Allow-Origin' => getenv('APP_FRONT_DOMAIN'),
            'Access-Control-Allow-Headers' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, PATCH, OPTIONS',
        ]);
    }
}
