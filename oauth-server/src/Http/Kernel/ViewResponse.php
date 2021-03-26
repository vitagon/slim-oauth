<?php

declare(strict_types=1);

namespace App\Http\Kernel;

use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;

class ViewResponse extends BaseResponse
{
    public function __construct($data = '', $status = 200, $headers = []) {
        parent::__construct(
            $status,
            new Headers($headers),
            (new StreamFactory())->createStream($data)
        );
    }
}
