<?php

declare(strict_types=1);

namespace App\OAuth\Dto;

use DateTimeImmutable;

class AuthCodeDto
{
    public string $id;
    public int $clientId;
    public int $userId;
    public string $scopes;
    public bool $revoked;
    public DateTimeImmutable $expiresAt;

    public static function create(
        string $id,
        int $clientId,
        int $userId,
        string $scopes,
        DateTimeImmutable $expiresAt
    ): self {
        $dto = new self();
        $dto->id = $id;
        $dto->clientId = $clientId;
        $dto->userId = $userId;
        $dto->scopes = $scopes;
        $dto->expiresAt = $expiresAt;

        return $dto;
    }
}
