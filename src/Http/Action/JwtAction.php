<?php

declare(strict_types=1);

namespace App\Http\Action;

use DateTimeImmutable;
use Lcobucci\JWT\Builder;
use App\Http\JsonResponse;
use Lcobucci\JWT\Configuration;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class JwtAction implements RequestHandlerInterface
{
    private Session $session;
    private Configuration $config;

    public function __construct(Session $session, Configuration $config)
    {
        $this->session = $session;
        $this->config = $config;
    }

    public function handle(Request $request): Response
    {
        $body = null;

        $now = new DateTimeImmutable();
        $token = $this->config->builder()
            // Configures the issuer (iss claim)
            ->issuedBy('http://example.com')
            // Configures the audience (aud claim)
            ->permittedFor('http://example.org')
            // Configures the id (jti claim)
            ->identifiedBy('4f1g23a12aa')
            // Configures the time that the token was issue (iat claim)
            ->issuedAt($now)
            // Configures the time that the token can be used (nbf claim)
            ->canOnlyBeUsedAfter($now->modify('+1 minute'))
            // Configures the expiration time of the token (exp claim)
            ->expiresAt($now->modify('+1 hour'))
            // Configures a new claim, called "uid"
            ->withClaim('uid', 1)
            // Configures a new header, called "foo"
            ->withHeader('foo', 'bar')
            // Builds a new token
            ->getToken($this->config->signer(), $this->config->signingKey());

        return new JsonResponse($token->toString());
    }
}
