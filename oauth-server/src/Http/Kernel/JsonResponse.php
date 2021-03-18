<?php

declare(strict_types=1);

namespace App\Http\Kernel;

use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;

class JsonResponse extends BaseResponse
{
    public function __construct($data, $status = 200, $headers = []) {
        parent::__construct(
            $status,
            new Headers(array_merge(['Content-Type' => 'application/json'], $headers)),
            (new StreamFactory())->createStream(json_encode($data, JSON_THROW_ON_ERROR))
        );
    }
}
