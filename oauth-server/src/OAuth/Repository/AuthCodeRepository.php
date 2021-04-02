<?php

declare(strict_types=1);

namespace App\OAuth\Repository;

use Doctrine\ORM\EntityManagerInterface;

class AuthCodeRepository
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getById(): AuthCode
    {

    }
}
