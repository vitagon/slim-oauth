<?php

declare(strict_types=1);

namespace App\OAuth\Model;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

/**
 * @ORM\Entity
 * @ORM\Table(name="oauth_clients")
 */
class Client
{
    /**
     * @Id()
     * @GeneratedValue()
     * @ORM\Column(type="integer")
     */
    public int $id;

    /**
     * @ORM\Column()
     */
    public string $name;

    /**
     * @ORM\Column()
     */
    public string $secret;

    /**
     * @ORM\Column
     */
    public string $redirect;

    /**
     * @ORM\Column
     */
    public bool $personalAccessClient;

    /**
     * @ORM\Column
     */
    public bool $passwordClient;

    /**
     * @ORM\Column
     */
    public bool $revoked;

    /**
     * @ORM\Column
     */
    public DateTime $updatedAt;

    /**
     * @ORM\Column
     */
    public DateTime $createdAt;

    public function confidential(): bool
    {
        return !empty($this->secret);
    }

    public function skipsAuthorization(): bool
    {
        return $this->personalAccessClient || $this->passwordClient;
    }
}
