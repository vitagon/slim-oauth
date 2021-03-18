<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\Http\Kernel\JsonResponse;
use Firebase\JWT\JWT;
use Lcobucci\JWT\Configuration;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class JwtAction implements RequestHandlerInterface
{
    private Session $session;
    private Configuration $config;

    public function __construct(Session $session, Configuration $config)
    {
        $this->session = $session;
        $this->config = $config;
    }

    public function handle(Request $request): Response
    {
        $jwt = JWT::encode(['uuid' => '123'], 'app_secret');
        return new JsonResponse($jwt);
    }
}
