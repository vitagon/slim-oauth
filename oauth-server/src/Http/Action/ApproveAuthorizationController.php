<?php

declare(strict_types=1);

namespace App\Http\Action;

use Exception;
use League\OAuth2\Server\AuthorizationServer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
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

    /**
     * @throws Exception
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->assertValidAuthToken($request, $this->session);

        $authRequest = $this->session->get('authRequest');
        if (!$authRequest) {
            throw new Exception('Authorization request was not present in the session.');
        }

        $authRequest->setAuthorizationApproved(true);
        return $this->server->completeAuthorizationRequest($authRequest, $response);
    }

    /**
     * @throws Exception
     */
    private function assertValidAuthToken(ServerRequestInterface $request, SessionInterface $session)
    {
        $reqParams = $request->getParsedBody();
        if (isset($reqParams['csrf_token']) && $session->get('csrfToken') !== $reqParams['csrf_token']) {
            $session->remove('csrfToken');
            $session->remove('authRequest');

            throw new Exception("Invalid csrf token");
        }
    }
}
