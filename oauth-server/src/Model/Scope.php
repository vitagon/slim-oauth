<?php

declare(strict_types=1);

namespace App\Model;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Id;

/**
 * @ORM\Entity
 * @ORM\Table(name="scopes")
 */
class Scope
{
    /**
     * @Id()
     * @ORM\Column(type="string")
     */
    public string $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public ?string $description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public ?DateTime $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public ?DateTime $createdAt;
}
