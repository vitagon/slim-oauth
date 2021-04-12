<?php

declare(strict_types=1);

namespace App\Model;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;

/**
 * @ORM\Entity()
 * @ORM\Table(name="auth_codes")
 */
class AuthCode
{
    /**
     * @Id()
     * @GeneratedValue()
     * @ORM\Column(type="integer")
     */
    public int $id;

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
     * @ORM\Column(type="datetime", nullable=true)
     */
    public DateTime $expiresAt;
}