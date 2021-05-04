<?php

declare(strict_types=1);

namespace App\OAuth\Dto;

use DateTime;
use DateTimeImmutable;
use JetBrains\PhpStorm\Pure;

class AccessTokenDto
{
    public string $id;
    public int $userId;
    public int $clientId;
    public string $scopes;
    public bool $revoked;
    public DateTimeImmutable $expiresAt;
    public DateTimeImmutable $createdAt;
    public DateTime $updatedAt;

    #[Pure]
    public static function create(
        string $id,
        int $userId,
        int $clientId,
        string $scopes,
        bool $revoked,
        DateTimeImmutable $expiresAt,
        DateTimeImmutable $createdAt,
        DateTime $updatedAt,
    ): self {
        $dto = new self();
        $dto->id = $id;
        $dto->userId = $userId;
        $dto->clientId = $clientId;
        $dto->scopes = $scopes;
        $dto->revoked = $revoked;
        $dto->expiresAt = $expiresAt;
        $dto->createdAt = $createdAt;
        $dto->updatedAt = $updatedAt;

        return $dto;
    }
}
