<?php

declare(strict_types=1);

namespace App\OAuth\Repository;

use App\OAuth\Dto\AuthCodeDto;
use App\OAuth\Model\AuthCodeEntity;
use App\OAuth\Trait\FormatScopesForStorage;
use App\Repository\AuthCodeRepository as AuthCodeModelRepository;
use Doctrine\ORM\NonUniqueResultException;
use JetBrains\PhpStorm\Pure;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;

class AuthCodeRepository implements AuthCodeRepositoryInterface
{
    use FormatScopesForStorage;

    private AuthCodeModelRepository $authCodes;

    public function __construct(AuthCodeModelRepository $authCodes)
    {
        $this->authCodes = $authCodes;
    }

    #[Pure]
    public function getNewAuthCode(): AuthCodeEntityInterface
    {
        return new AuthCodeEntity();
    }

    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity): void
    {
        $attributes = [
            'id' => $authCodeEntity->getIdentifier(),
            'user_id' => $authCodeEntity->getUserIdentifier(),
            'client_id' => $authCodeEntity->getClient()->getIdentifier(),
            'scopes' => $this->formatScopesForStorage($authCodeEntity->getScopes()),
            'revoked' => false,
            'expires_at' => $authCodeEntity->getExpiryDateTime(),
        ];

        $dto = AuthCodeDto::create(
            $attributes['id'],
            (int)$attributes['client_id'],
            (int)$attributes['user_id'],
            $attributes['scopes'],
            $attributes['expires_at']
        );
        $this->authCodes->save($dto);
    }

    /**
     * @param string $codeId
     * @throws NonUniqueResultException
     */
    public function revokeAuthCode($codeId): void
    {
        $authCode = $this->authCodes->getById($codeId);
        $authCode->revoked = true;
        $this->authCodes->update($authCode);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function isAuthCodeRevoked($codeId): bool
    {
        $authCode = $this->authCodes->getById($codeId);
        return $authCode->revoked;
    }
}
