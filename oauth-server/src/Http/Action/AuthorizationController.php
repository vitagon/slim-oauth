<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\Http\Kernel\JsonResponse;
use App\Http\Security\SecurityContext;
use App\OAuth\Model\UserEntity;
use App\Repository\ClientRepository;
use App\Repository\ScopeRepository;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use JsonException;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Stream;
use Slim\Views\Twig;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AuthorizationController
{
    private AuthorizationServer $server;
    private ClientRepository $clientRepository;
    private ScopeRepository $scopeRepository;
    private SessionInterface $session;
    private Twig $twig;
    private SecurityContext $securityContext;

    public function __construct(
        AuthorizationServer $server,
        SecurityContext $securityContext,
        ClientRepository $clientRepository,
        ScopeRepository $scopeRepository,
        Twig $twig,
        SessionInterface $session
    ) {
        $this->server = $server;
        $this->securityContext = $securityContext;
        $this->clientRepository = $clientRepository;
        $this->scopeRepository = $scopeRepository;
        $this->twig = $twig;
        $this->session = $session;
    }

    /**
     * @throws JsonException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            // Validate the HTTP request and return an AuthorizationRequest object.
            $authRequest = $this->server->validateAuthorizationRequest($request);

            $userId = $this->securityContext->getUser()->getId();
            // Once the user has logged in set the user on the AuthorizationRequest
            $authRequest->setUser(new UserEntity($userId)); // an instance of UserEntityInterface
            
            $scopes = $this->parseScopes($authRequest);

            // At this point you should redirect the user to an authorization page.
            // This form will ask the user to approve the client and the scopes requested.
            $clientId = $authRequest->getClient()->getIdentifier();
            $client = $this->clientRepository->getById($clientId);
            if ($client && $client->skipsAuthorization()) {
                return $this->approveRequest($authRequest, $response);
            }

            $csrfToken = bin2hex(random_bytes(30));
            $this->session->set('authRequest', $authRequest);
            $this->session->set('csrfToken', $csrfToken);

            return $this->twig->render($response, 'authorize.html.twig', [
                'client_id' => $clientId,
                'csrf_token' => $csrfToken,
                'scopes' => $scopes,
            ]);
        } catch (OAuthServerException $exception) {
            return new JsonResponse($exception->getPayload());
        } catch (Exception $exception) {
            // Unknown exception
            $body = new Stream(fopen('php://temp', 'r+'));
            $body->write($exception->getMessage());
            return $response->withStatus(500)->withBody($body);
        }
    }

    private function approveRequest(AuthorizationRequest $authRequest, ResponseInterface $response): ResponseInterface
    {
        // Once the user has approved or denied the client update the status
        // (true = approved, false = denied)
        $authRequest->setAuthorizationApproved(true);

        // Return the HTTP redirect response
        return $this->server->completeAuthorizationRequest($authRequest, $response);
    }

    /**
     * @throws NonUniqueResultException
     */
    private function parseScopes(AuthorizationRequest $authRequest): array
    {
        $scopes = $authRequest->getScopes();

        $scopesArr = [];
        foreach ($scopes as $scope) {
            if ($scope->getIdentifier() === '*') {
                $scopeModels = $this->scopeRepository->getAll();
                $scopesArr = array_map(fn($s) => $s->description, $scopeModels);
                break;
            }

            $scopeModel = $this->scopeRepository->getById($scope->getIdentifier());
            $scopesArr[] = $scopeModel->description;
        }

        return $scopesArr;
    }
}
