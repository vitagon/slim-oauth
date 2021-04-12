<?php

declare(strict_types=1);

namespace App\Http\Action;

use Slim\Psr7\Response;

class BaseController
{
    public function redirect(string $to): Response
    {
        return (new Response())
            ->withHeader('Location', $to)
            ->withStatus(302);
    }
}
