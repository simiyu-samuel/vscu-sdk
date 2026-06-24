<?php

declare(strict_types=1);

namespace SimiyuSamuel\VscuSdk\Contracts;

use Illuminate\Http\Client\Response;

interface VscuTransport
{
    /**
     * @param array<string, mixed> $payload
     */
    public function post(string $path, array $payload): Response;

    /**
     * @param array<string, mixed> $query
     */
    public function get(string $path, array $query = []): Response;
}
