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
use Slim\Views\Twig;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LoginController
{
    private AuthService $authService;
    private SessionInterface $session;
    private Twig $view;

    public function __construct(AuthService $authService, SessionInterface $session, Twig $view)
    {
        $this->authService = $authService;
        $this->session = $session;
        $this->view = $view;
    }

    public function show(Response $response): Response
    {
        return $this->view->render($response, 'login.html.twig');
    }

    public function login(Request $request): Response
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
        $this->session->set('user', $user);

        return new JsonResponse();
    }
}
