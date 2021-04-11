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
 * @ORM\Table(name="refresh_tokens", indexes={
 *     @ORM\Index(name="access_token", columns={"access_token_id"})
 * })
 */
class RefreshToken
{
    /**
     * @Id()
     * @GeneratedValue()
     * @ORM\Column(type="integer")
     */
    public int $id;

    /**
     * @ORM\ManyToOne(targetEntity="AccessToken")
     * @ORM\JoinColumn(name="access_token_id", referencedColumnName="id")
     */
    public AccessToken $accessToken;

    /**
     * @ORM\Column(type="boolean")
     */
    public bool $revoked;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public DateTime $expiresAt;
}
