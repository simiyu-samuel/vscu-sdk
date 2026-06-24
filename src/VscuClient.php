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

    /**
     * @param array<string, mixed> $payload
     */
    private function post(string $path, array $payload): Response
    {
        return Http::timeout($this->timeout)
            ->post($this->baseUrl . $path, PayloadFormatter::format($payload));
    }

    /**
     * @param array<string, mixed> $query
     */
    private function get(string $path, array $query = []): Response
    {
        return Http::timeout($this->timeout)
            ->get($this->baseUrl . $path, $query);
    }

    public function initializeDevice(DeviceInitDTO|array $payload): Response
    {
        $dto = $payload instanceof DeviceInitDTO ? $payload : DeviceInitDTO::make($payload);

        return $this->post('/initializer/selectInitInfo', $dto->toPayload());
    }

    public function initializeDeviceResult(DeviceInitDTO|array $payload): VscuResponseDTO
    {
        return VscuResponseDTO::fromArray($this->initializeDevice($payload)->json() ?? []);
    }

    public function saveSales(InvoiceDTO|array $payload): Response
    {
        $dto = $payload instanceof InvoiceDTO ? $payload : InvoiceDTO::make($payload);

        return $this->post('/trnsSales/saveSales', $dto->toPayload());
    }

    public function saveSalesResult(InvoiceDTO|array $payload): VscuResponseDTO
    {
        return VscuResponseDTO::fromArray($this->saveSales($payload)->json() ?? []);
    }

    public function saveCreditNote(CreditNoteDTO|array $payload): Response
    {
        $dto = $payload instanceof CreditNoteDTO ? $payload : CreditNoteDTO::make($payload);

        return $this->post('/trnsSales/saveSales', $dto->toInvoicePayload());
    }

    public function saveCreditNoteResult(CreditNoteDTO|array $payload): VscuResponseDTO
    {
        return VscuResponseDTO::fromArray($this->saveCreditNote($payload)->json() ?? []);
    }

    public function saveDebitNote(DebitNoteDTO|array $payload): Response
    {
        $dto = $payload instanceof DebitNoteDTO ? $payload : DebitNoteDTO::make($payload);

        return $this->post('/trnsSales/saveSales', $dto->toInvoicePayload());
    }

    public function saveDebitNoteResult(DebitNoteDTO|array $payload): VscuResponseDTO
    {
        return VscuResponseDTO::fromArray($this->saveDebitNote($payload)->json() ?? []);
    }

    public function getCodes(string $tpin, string $bhfId, string $lastReqDt): Response
    {
        return $this->get('/code/selectCodes', [
            'tpin' => $tpin,
            'bhfId' => $bhfId,
            'lastReqDt' => $lastReqDt,
        ]);
    }

    public function getCodesResult(string $tpin, string $bhfId, string $lastReqDt): VscuResponseDTO
    {
        return VscuResponseDTO::fromArray($this->getCodes($tpin, $bhfId, $lastReqDt)->json() ?? []);
    }

    public function getItemClassifications(string $tpin, string $bhfId, string $lastReqDt): Response
    {
        return $this->get('/itemClass/selectItemsClass', [
            'tpin' => $tpin,
            'bhfId' => $bhfId,
            'lastReqDt' => $lastReqDt,
        ]);
    }

    public function getItemClassificationsResult(string $tpin, string $bhfId, string $lastReqDt): VscuResponseDTO
    {
        return VscuResponseDTO::fromArray($this->getItemClassifications($tpin, $bhfId, $lastReqDt)->json() ?? []);
    }

    public function getCustomerByPin(string $tpin, string $bhfId, string $customerPin): Response
    {
        return $this->get('/customers/selectCustomer', [
            'tpin' => $tpin,
            'bhfId' => $bhfId,
            'custTpin' => $customerPin,
        ]);
    }

    public function getCustomerByPinResult(string $tpin, string $bhfId, string $customerPin): VscuResponseDTO
    {
        return VscuResponseDTO::fromArray($this->getCustomerByPin($tpin, $bhfId, $customerPin)->json() ?? []);
    }

    public function getItems(string $tpin, string $bhfId, string $lastReqDt): Response
    {
        return $this->get('/items/selectItems', [
            'tpin' => $tpin,
            'bhfId' => $bhfId,
            'lastReqDt' => $lastReqDt,
        ]);
    }

    public function getItemsResult(string $tpin, string $bhfId, string $lastReqDt): VscuResponseDTO
    {
        return VscuResponseDTO::fromArray($this->getItems($tpin, $bhfId, $lastReqDt)->json() ?? []);
    }
}
