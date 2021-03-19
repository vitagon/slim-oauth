<?php

declare(strict_types=1);

namespace App\Http\Action\Options;

use App\Http\Kernel\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;

class OptionsAction implements RequestHandlerInterface
{
    public function handle(Request $request): Response
    {
        return new JsonResponse([]);
    }
}
