<?php

declare(strict_types=1);

namespace App\Http\Security;

use App\Model\AccessToken;
use App\Model\Client;
use App\Model\User;
use App\Repository\AccessTokenRepository;
use App\Service\UserService;
use Doctrine\ORM\NonUniqueResultException;
use OAuthException;
use Psr\Http\Message\ServerRequestInterface;

class OAuthSecurityContext
{
    // this keys are set as request attributes by ResourceServerMiddleware
    private const ACCESS_TOKEN_ID_KEY = 'oauth_access_token_id';
    private const CLIENT_ID_KEY = 'oauth_client_id';
    private const USER_ID_KEY = 'oauth_user_id';
    private const SCOPES_KEY = 'oauth_scopes';

    private UserService $userService;
    private AccessTokenRepository $accessTokenRepository;

    public function __construct(UserService $userService, AccessTokenRepository $accessTokenRepository)
    {
        $this->userService = $userService;
        $this->accessTokenRepository = $accessTokenRepository;
    }

    /**
     * @throws OAuthException
     */
    public function getUser(ServerRequestInterface $request): ?User
    {
        $this->checkIfAttributeExists($request, self::USER_ID_KEY);
        return $this->userService->getById((int)$request->getAttribute(self::USER_ID_KEY));
    }

    /**
     * @throws OAuthException
     * @throws NonUniqueResultException
     */
    public function getAccessToken(ServerRequestInterface $request): ?AccessToken
    {
        $this->checkIfAttributeExists($request, self::ACCESS_TOKEN_ID_KEY);
        return $this->accessTokenRepository->getById($request->getAttribute(self::ACCESS_TOKEN_ID_KEY));
    }

    public function getClient(): ?Client
    {
        // TODO:
        return null;
    }

    public function getScopes(): array
    {
        // TODO:
        return [];
    }

    /**
     * @throws OAuthException
     */
    private function checkIfAttributeExists(ServerRequestInterface $request, string $attrName): void
    {
        $attrVal = $request->getAttribute($attrName);
        if (!$attrVal) {
            throw new OAuthException(sprintf('%s was not found in request attributes.', $attrName));
        }
    }
}
