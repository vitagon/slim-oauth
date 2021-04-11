<?php

declare(strict_types=1);

namespace App\Model;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

/**
 * @ORM\Entity
 * @ORM\Table(name="clients")
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
     * @ORM\Column(type="string")
     */
    public string $name;

    /**
     * @ORM\Column(type="string")
     */
    public string $secret;

    /**
     * @ORM\Column(type="string")
     */
    public string $redirect;

    /**
     * @ORM\Column(type="boolean")
     */
    public bool $personalAccessClient;

    /**
     * @ORM\Column(type="boolean")
     */
    public bool $passwordClient;

    /**
     * @ORM\Column(type="boolean")
     */
    public bool $revoked;

    /**
     * @ORM\Column(type="datetime")
     */
    public DateTime $updatedAt;

    /**
     * @ORM\Column(type="datetime")
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
