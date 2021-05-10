<?php

declare(strict_types=1);

namespace App\Http\Kernel;

use JsonException;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;

class JsonResponse extends BaseResponse
{
    public function __construct($data = [], $status = 200, $headers = []) {
        try {
            $content = json_encode($data, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            $content = json_encode(['error' => [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ]]);
        }

        parent::__construct(
            $status,
            new Headers(array_merge(['Content-Type' => 'application/json'], $headers)),
            (new StreamFactory())->createStream($content)
        );
    }
}
