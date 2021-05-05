<?php

declare(strict_types=1);

namespace App\Model;

use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use JetBrains\PhpStorm\Pure;

/**
 * @ORM\Entity()
 * @ORM\Table(name="access_tokens")
 */
class AccessToken
{
    /**
     * @Id()
     * @ORM\Column(type="string")
     */
    public string $id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    public User $user;

    /**
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    public Client $client;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public ?string $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    public string $scopes;

    /**
     * @ORM\Column(type="boolean")
     */
    public bool $revoked;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    public DateTimeImmutable $expiresAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    public DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    public DateTime $updatedAt;

    #[Pure]
    public static function create(
        string $id,
        Client $client,
        User $user,
        string $scopes,
        DateTimeImmutable $expiresAt,
        DateTimeImmutable $createdAt,
        DateTime $updatedAt,
    ): self {
        $accessToken = new self();
        $accessToken->id = $id;
        $accessToken->client = $client;
        $accessToken->user = $user;
        $accessToken->scopes = $scopes;
        $accessToken->revoked = false;
        $accessToken->expiresAt = $expiresAt;
        $accessToken->createdAt = $createdAt;
        $accessToken->updatedAt = $updatedAt;

        return $accessToken;
    }

    public function revoke(): void
    {
        $this->revoked = true;
    }
}
