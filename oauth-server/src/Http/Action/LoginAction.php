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

        $response = new JsonResponse([
            'user' => $user,
            'password' => password_hash('123123', PASSWORD_BCRYPT),
            'token' => $token,
        ]);

        $cookies = (new Cookies())
            ->set('X-Auth', [
                'value' => $token,
                'path' => '/',
                'expires' => time() + 3600,
                'domain' => sprintf('.%s', parse_url(getenv('APP_FRONT_DOMAIN'), PHP_URL_HOST)),
            ]);

        return $response->withCookies($cookies);
    }
}
