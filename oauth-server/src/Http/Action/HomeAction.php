<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\Http\Kernel\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use stdClass;
use Symfony\Component\HttpFoundation\Session\Session;

class HomeAction
{
    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function __invoke(Request $request): ResponseInterface
    {
        $this->session->set('msg', 'Updated msg 111');

        return new JsonResponse(new stdClass());
    }
}
