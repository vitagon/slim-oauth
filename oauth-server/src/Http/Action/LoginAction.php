<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\Http\Kernel\JsonResponse;
use App\Service\AuthService;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpException;
use Slim\Psr7\Cookies;

class LoginAction implements RequestHandlerInterface
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function handle(Request $request): Response
    {
        $user = null;
        try {
            $user = $this->authService->getUser($request->getParsedBody());
        } catch (Exception $e) {
            throw new HttpException($request, $e->getMessage(), 500);
        }

        if (!$user) {
            throw new HttpException($request, 'Invalid credentials', 401);
        }

        $token = $this->authService->createToken($user);
        $cookies = (new Cookies())
            ->set('_token', [
                'value' => $token,
                'path' => '/',
                'expires' => time() + 3600,
                'domain' => 'company.loc'
            ]);

        $response = new JsonResponse([
            'user' => $user,
            'password' => password_hash('123123', PASSWORD_BCRYPT),
            'token' => $token,
        ], 200, [
            'Access-Control-Allow-Origin' => getenv('APP_FRONT_DOMAIN'),
            'Access-Control-Allow-Headers' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, PATCH, OPTIONS',
        ]);
        return $response->withCookies($cookies);
    }
}
