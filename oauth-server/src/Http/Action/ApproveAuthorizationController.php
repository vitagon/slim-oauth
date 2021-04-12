<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\Model\User;
use App\OAuth\Model\UserEntity;
use Exception;
use League\OAuth2\Server\AuthorizationServer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ApproveAuthorizationController
{
    private AuthorizationServer $server;
    private SessionInterface $session;

    public function __construct(AuthorizationServer $server, SessionInterface $session)
    {
        $this->server = $server;
        $this->session = $session;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->assertValidAuthToken($request, $this->session);

        $authRequest = $this->session->get('authRequest');
        if (! $authRequest) {
            throw new Exception('Authorization request was not present in the session.');
        }

        $authRequest->setAuthorizationApproved(true);
        return $this->server->completeAuthorizationRequest($authRequest, $response);
    }

    private function assertValidAuthToken(Request $request, SessionInterface $session)
    {
        $reqParams = $request->getParsedBody();
        if (isset($reqParams['auth_token']) && $session->get('authToken') !== $reqParams['auth_token']) {
            $session->remove('authToken');
            $session->remove('authRequest');

            throw new \Exception("Invalid auth token");
        }
    }
}
