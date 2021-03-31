<?php

declare(strict_types=1);

namespace App\OAuth;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

class Client
{
    #[Id]
    #[]
    public int $id;
    public string $name;
    public string $secret;
    public string $redirect;

    public bool $personalAccessClient;
    public bool $passwordClient;
    public bool $revoked;
    public DateTimeImmutable $updatedAt;
    public DateTimeImmutable $createdAt;
}
