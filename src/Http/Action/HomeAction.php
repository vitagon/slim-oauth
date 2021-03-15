<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\Http\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use stdClass;

class HomeAction implements RequestHandlerInterface
{
    public function handle(Request $request): Response
    {
        return new JsonResponse(new stdClass());
    }
}
