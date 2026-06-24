<?php

declare(strict_types=1);

namespace SimiyuSamuel\VscuSdk\Transport;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use SimiyuSamuel\VscuSdk\Contracts\VscuTransport;
use SimiyuSamuel\VscuSdk\Support\PayloadFormatter;

final class HttpVscuTransport implements VscuTransport
{
    /**
     * @param array<string, string> $headers
     */
    public function __construct(
        private readonly string $baseUrl,
        private readonly int $timeout = 90,
        private readonly array $headers = [],
    ) {}

    /**
     * @param array<string, mixed> $payload
     */
    public function post(string $path, array $payload): Response
    {
        return Http::timeout($this->timeout)
            ->withHeaders($this->headers)
            ->post($this->baseUrl . $path, PayloadFormatter::format($payload));
    }

    /**
     * @param array<string, mixed> $query
     */
    public function get(string $path, array $query = []): Response
    {
        return Http::timeout($this->timeout)
            ->withHeaders($this->headers)
            ->get($this->baseUrl . $path, $query);
    }
}
