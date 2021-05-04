<?php

declare(strict_types=1);

namespace App\Http\Action\Options;

use App\Http\Kernel\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;

class OptionsAction
{
    public function __invoke(Request $request, ResponseInterface $response): Response
    {
        return new JsonResponse([]);
    }
}
