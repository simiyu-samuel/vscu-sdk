<?php

declare(strict_types=1);

namespace SimiyuSamuel\VscuSdk;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use SimiyuSamuel\VscuSdk\DTOs\DeviceInitDTO;
use SimiyuSamuel\VscuSdk\DTOs\InvoiceDTO;
use SimiyuSamuel\VscuSdk\Support\PayloadFormatter;

final class VscuClient
{
    public function __construct(
        private readonly string $baseUrl = 'http://localhost:8088',
        private readonly int $timeout = 90,
    ) {}

    public function initializeDevice(DeviceInitDTO|array $payload): Response
    {
        $dto = $payload instanceof DeviceInitDTO ? $payload : DeviceInitDTO::make($payload);

        return Http::timeout($this->timeout)
            ->post($this->baseUrl . '/initializer/selectInitInfo', PayloadFormatter::format($dto->toPayload()));
    }

    public function saveSales(InvoiceDTO|array $payload): Response
    {
        $dto = $payload instanceof InvoiceDTO ? $payload : InvoiceDTO::make($payload);

        return Http::timeout($this->timeout)
            ->post($this->baseUrl . '/trnsSales/saveSales', PayloadFormatter::format($dto->toPayload()));
    }
}
