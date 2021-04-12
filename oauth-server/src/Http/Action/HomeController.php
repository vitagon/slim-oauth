<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\Http\Security\SecurityContext;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class HomeController
{
    private Twig $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke(Response $response, SecurityContext $securityContext): ResponseInterface
    {
        $user = $securityContext->getUser();

        return $this->twig->render($response, 'home.html.twig', compact('user'));
    }
}
