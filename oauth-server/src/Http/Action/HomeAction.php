<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\Http\Kernel\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use stdClass;
use Symfony\Component\HttpFoundation\Session\Session;

class HomeAction implements RequestHandlerInterface
{
    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function handle(Request $request): Response
    {
        $this->session->set('msg', 'Updated msg 111');

        return new JsonResponse(new stdClass());
    }
}
