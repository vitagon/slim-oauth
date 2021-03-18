<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\Http\Kernel\JsonResponse;
use DateInterval;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\PermittedFor;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\StrictValidAt;
use Lcobucci\JWT\Validation\RequiredConstraintsViolated;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;

class ValidateJwtAction implements RequestHandlerInterface
{
    private Configuration $config;

    public function __construct(Configuration $config)
    {
        $this->config = $config;
    }

    public function handle(Request $request): Response
    {
        $jwtHeader = $request->getHeader('X-Jwt');
        $jwt = $jwtHeader ? $jwtHeader[0] : null;

        if ($jwt) {
            $token = $this->config->parser()->parse($jwt);
            assert($token instanceof UnencryptedToken);

            $constraints = $this->config->validationConstraints();
            $constraints = array_merge($constraints, [
                new IssuedBy('http://example.com'),
                new PermittedFor('http://example.org'),
                new SignedWith($this->config->signer(), $this->config->signingKey()),
                new StrictValidAt(SystemClock::fromUTC(), new DateInterval('PT1H'))
            ]);

            try {
                $this->config->validator()->assert($token, ...$constraints);
            } catch (RequiredConstraintsViolated $e) {
                var_dump($e->violations());
                die();
            }

            return new JsonResponse('Token is valid.');
        }

        return new JsonResponse('Token header was not found.');
    }
}
