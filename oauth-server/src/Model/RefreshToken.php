<?php

declare(strict_types=1);

namespace App\Model;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Id;

/**
 * @ORM\Entity()
 * @ORM\Table(name="refresh_tokens", indexes={
 *     @ORM\Index(name="access_token", columns={"access_token_id"})
 * })
 */
class RefreshToken
{
    /**
     * @Id()
     * @ORM\Column
     */
    public string $id;

    /**
     * @ORM\Column
     */
    public string $accessTokenId;

    /**
     * @ORM\Column(type="boolean")
     */
    public bool $revoked;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    public DateTimeImmutable $expiresAt;
}
