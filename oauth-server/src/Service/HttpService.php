<?php

declare(strict_types=1);

namespace App\Service;

use Psr\Http\Message\ServerRequestInterface;

class HttpService
{
    public function getToken(ServerRequestInterface $request): string
    {
        $token = $this->getTokenFromCookie($request);
        if (!$token) {
            $token = $this->getTokenFromHeader($request);
        }
        return $token;
    }

    private function getTokenFromCookie(ServerRequestInterface $request): ?string
    {
        $cookies = $request->getCookieParams();
        if (!isset($cookies['_token'])) {
            return null;
        }
        return $cookies['_token'];
    }

    private function getTokenFromHeader(ServerRequestInterface $request): ?string
    {
        $authHeader = $request->getHeader('Authorization');
        $authHeaderVal = $authHeader ? $authHeader[0] : null;

        $token = null;
        if ($authHeaderVal) {
            $authHeaderValArr = explode(' ', $authHeaderVal);
            $token = isset($authHeaderValArr[1]) ? $authHeaderValArr[1] : null;
        }

        return $token;
    }
}
