<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\Http\Kernel\JsonResponse;
use App\Service\HttpService;
use App\Service\UserService;
use Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class ProfileAction implements RequestHandlerInterface
{
    private Session $session;
    private HttpService $httpService;
    private UserService $userService;

    public function __construct(Session $session, HttpService $httpService, UserService $userService)
    {
        $this->session = $session;
        $this->httpService = $httpService;
        $this->userService = $userService;
    }

    public function handle(Request $request): Response
    {
        $token = $this->httpService->getToken($request);
        $jwt = JWT::decode($token, 'app_secret', ['HS256']);
        $jwtArr = (array) $jwt;

        $user = $this->userService->getUser($jwtArr['uid']);

        return new JsonResponse([
//            'msg' => $this->session->get('msg'),
//            'token' => $token,
//            'jwt' => $jwtArr,
//            'password' => password_hash('123123', PASSWORD_BCRYPT),
            'user' => $user
        ]);
    }
}
