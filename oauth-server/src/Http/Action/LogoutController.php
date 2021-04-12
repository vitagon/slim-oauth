<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\Http\Kernel\JsonResponse;
use App\Http\Security\SecurityContext;
use App\Service\AuthService;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LogoutController extends BaseController
{
    public function __invoke(SecurityContext $securityContext): Response
    {
        if ($securityContext->getUser()) {
            $securityContext->logout();
        }

        return $this->redirect('/');
    }
}
