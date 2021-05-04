<?php

declare(strict_types=1);

namespace App\Model;

use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use JetBrains\PhpStorm\Pure;

/**
 * @ORM\Entity()
 * @ORM\Table(name="auth_codes")
 */
class AuthCode
{
    /**
     * @Id()
     * @ORM\Column(type="string")
     */
    public string $id;

    /**
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    public Client $client;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    public User $user;

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
    public ?DateTimeImmutable $expiresAt;

    #[Pure]
    public static function create(string $id, Client $client, User $user, string $scopes, ?DateTimeImmutable $expiresAt): self
    {
        $authCode = new self();
        $authCode->id = $id;
        $authCode->client = $client;
        $authCode->user = $user;
        $authCode->scopes = $scopes;
        $authCode->revoked = false;
        $authCode->expiresAt = $expiresAt;

        return $authCode;
    }
}
