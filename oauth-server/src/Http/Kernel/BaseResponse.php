<?php

declare(strict_types=1);

namespace App\Http\Kernel;

use Slim\Psr7\Cookies;
use Slim\Psr7\Response;

class BaseResponse extends Response
{
    public function withCookies(Cookies $cookies): self
    {
        return $this->withHeader('Set-Cookie', $cookies->toHeaders());
    }
}
