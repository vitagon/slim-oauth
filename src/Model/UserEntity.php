<?php

declare(strict_types=1);

namespace App\Model;

use League\OAuth2\Server\Entities\UserEntityInterface;

class UserEntity implements UserEntityInterface
{
    public function getIdentifier()
    {
        return 455;
    }
}
