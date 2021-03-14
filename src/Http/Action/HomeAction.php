<?php

declare(strict_types=1);

namespace App\Http\Action;

use Slim\Exception\HttpException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use stdClass;

class HomeAction
{
    public function __invoke(Request $request, Response $response, $args): Response
    {
//        throw new HttpException($request, "Validation error", 422);
        $response->getBody()->write(json_encode(new stdClass()));
        return $response->withHeader('Content-Type', 'application/json');
    }
}