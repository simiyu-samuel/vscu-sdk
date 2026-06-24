<?php

declare(strict_types=1);

namespace SimiyuSamuel\VscuSdk;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use SimiyuSamuel\VscuSdk\DTOs\CreditNoteDTO;
use SimiyuSamuel\VscuSdk\DTOs\DeviceInitDTO;
use SimiyuSamuel\VscuSdk\DTOs\DebitNoteDTO;
use SimiyuSamuel\VscuSdk\DTOs\InvoiceDTO;
use SimiyuSamuel\VscuSdk\DTOs\VscuResponseDTO;
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

    public function initializeDeviceResult(DeviceInitDTO|array $payload): VscuResponseDTO
    {
        return VscuResponseDTO::fromArray($this->initializeDevice($payload)->json() ?? []);
    }

    public function saveSales(InvoiceDTO|array $payload): Response
    {
        $dto = $payload instanceof InvoiceDTO ? $payload : InvoiceDTO::make($payload);

        return Http::timeout($this->timeout)
            ->post($this->baseUrl . '/trnsSales/saveSales', PayloadFormatter::format($dto->toPayload()));
    }

    public function saveSalesResult(InvoiceDTO|array $payload): VscuResponseDTO
    {
        return VscuResponseDTO::fromArray($this->saveSales($payload)->json() ?? []);
    }

    public function saveCreditNote(CreditNoteDTO|array $payload): Response
    {
        $dto = $payload instanceof CreditNoteDTO ? $payload : CreditNoteDTO::make($payload);

        return Http::timeout($this->timeout)
            ->post($this->baseUrl . '/trnsSales/saveSales', PayloadFormatter::format($dto->toInvoicePayload()));
    }

    public function saveCreditNoteResult(CreditNoteDTO|array $payload): VscuResponseDTO
    {
        return VscuResponseDTO::fromArray($this->saveCreditNote($payload)->json() ?? []);
    }

    public function saveDebitNote(DebitNoteDTO|array $payload): Response
    {
        $dto = $payload instanceof DebitNoteDTO ? $payload : DebitNoteDTO::make($payload);

        return Http::timeout($this->timeout)
            ->post($this->baseUrl . '/trnsSales/saveSales', PayloadFormatter::format($dto->toInvoicePayload()));
    }

    public function saveDebitNoteResult(DebitNoteDTO|array $payload): VscuResponseDTO
    {
        return VscuResponseDTO::fromArray($this->saveDebitNote($payload)->json() ?? []);
    }
}
