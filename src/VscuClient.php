<?php

declare(strict_types=1);

namespace SimiyuSamuel\VscuSdk;

use Illuminate\Http\Client\Response;
use SimiyuSamuel\VscuSdk\Contracts\VscuTransport;
use SimiyuSamuel\VscuSdk\DTOs\CreditNoteDTO;
use SimiyuSamuel\VscuSdk\DTOs\DeviceInitDTO;
use SimiyuSamuel\VscuSdk\DTOs\DebitNoteDTO;
use SimiyuSamuel\VscuSdk\DTOs\InvoiceDTO;
use SimiyuSamuel\VscuSdk\DTOs\StockMasterDTO;
use SimiyuSamuel\VscuSdk\DTOs\StockMovementDTO;
use SimiyuSamuel\VscuSdk\DTOs\VscuResponseDTO;
use SimiyuSamuel\VscuSdk\Transport\HttpVscuTransport;

final class VscuClient
{
    private ?VscuTransport $transport = null;

    public function __construct(
        private readonly string $baseUrl = 'http://localhost:8088',
        private readonly int $timeout = 90,
        /**
         * @var array<string, string>
         */
        private readonly array $headers = [],
        ?VscuTransport $transport = null,
    ) {
        $this->transport = $transport;
    }

    private function transport(): VscuTransport
    {
        return $this->transport ??= new HttpVscuTransport(
            baseUrl: $this->baseUrl,
            timeout: $this->timeout,
            headers: $this->headers,
        );
    }

    public function initializeDevice(DeviceInitDTO|array $payload): Response
    {
        $dto = $payload instanceof DeviceInitDTO ? $payload : DeviceInitDTO::make($payload);

        return $this->transport()->post('/initializer/selectInitInfo', $dto->toPayload());
    }

    public function initializeDeviceResult(DeviceInitDTO|array $payload): VscuResponseDTO
    {
        return VscuResponseDTO::fromArray($this->initializeDevice($payload)->json() ?? []);
    }

    public function saveSales(InvoiceDTO|array $payload): Response
    {
        $dto = $payload instanceof InvoiceDTO ? $payload : InvoiceDTO::make($payload);

        return $this->transport()->post('/trnsSales/saveSales', $dto->toPayload());
    }

    public function saveSalesResult(InvoiceDTO|array $payload): VscuResponseDTO
    {
        return VscuResponseDTO::fromArray($this->saveSales($payload)->json() ?? []);
    }

    public function saveCreditNote(CreditNoteDTO|array $payload): Response
    {
        $dto = $payload instanceof CreditNoteDTO ? $payload : CreditNoteDTO::make($payload);

        return $this->transport()->post('/trnsSales/saveSales', $dto->toInvoicePayload());
    }

    public function saveCreditNoteResult(CreditNoteDTO|array $payload): VscuResponseDTO
    {
        return VscuResponseDTO::fromArray($this->saveCreditNote($payload)->json() ?? []);
    }

    public function saveDebitNote(DebitNoteDTO|array $payload): Response
    {
        $dto = $payload instanceof DebitNoteDTO ? $payload : DebitNoteDTO::make($payload);

        return $this->transport()->post('/trnsSales/saveSales', $dto->toInvoicePayload());
    }

    public function saveDebitNoteResult(DebitNoteDTO|array $payload): VscuResponseDTO
    {
        return VscuResponseDTO::fromArray($this->saveDebitNote($payload)->json() ?? []);
    }

    public function getCodes(string $tpin, string $bhfId, string $lastReqDt): Response
    {
        return $this->transport()->post('/code/selectCodes', [
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
        return $this->transport()->post('/itemClass/selectItemsClass', [
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
        return $this->transport()->post('/customers/selectCustomer', [
            'tpin' => $tpin,
            'bhfId' => $bhfId,
            'custmTin' => $customerPin,
        ]);
    }

    public function getCustomerByPinResult(string $tpin, string $bhfId, string $customerPin): VscuResponseDTO
    {
        return VscuResponseDTO::fromArray($this->getCustomerByPin($tpin, $bhfId, $customerPin)->json() ?? []);
    }

    public function getItems(string $tpin, string $bhfId, string $lastReqDt): Response
    {
        return $this->transport()->post('/items/selectItems', [
            'tpin' => $tpin,
            'bhfId' => $bhfId,
            'lastReqDt' => $lastReqDt,
        ]);
    }

    public function getItemsResult(string $tpin, string $bhfId, string $lastReqDt): VscuResponseDTO
    {
        return VscuResponseDTO::fromArray($this->getItems($tpin, $bhfId, $lastReqDt)->json() ?? []);
    }

    public function saveStockMovement(StockMovementDTO|array $payload): Response
    {
        $dto = $payload instanceof StockMovementDTO ? $payload : StockMovementDTO::make($payload);

        return $this->transport()->post('/stock/saveStockItems', $dto->toPayload());
    }

    public function saveStockMovementResult(StockMovementDTO|array $payload): VscuResponseDTO
    {
        return VscuResponseDTO::fromArray($this->saveStockMovement($payload)->json() ?? []);
    }

    public function getStockMovements(string $tpin, string $bhfId, string $lastReqDt): Response
    {
        return $this->transport()->post('/stock/selectStockItems', [
            'tpin' => $tpin,
            'bhfId' => $bhfId,
            'lastReqDt' => $lastReqDt,
        ]);
    }

    public function getStockMovementsResult(string $tpin, string $bhfId, string $lastReqDt): VscuResponseDTO
    {
        return VscuResponseDTO::fromArray($this->getStockMovements($tpin, $bhfId, $lastReqDt)->json() ?? []);
    }

    public function saveStockMaster(StockMasterDTO|array $payload): Response
    {
        $dto = $payload instanceof StockMasterDTO ? $payload : StockMasterDTO::make($payload);

        return $this->transport()->post('/stockMaster/saveStockMaster', $dto->toPayload());
    }

    public function saveStockMasterResult(StockMasterDTO|array $payload): VscuResponseDTO
    {
        return VscuResponseDTO::fromArray($this->saveStockMaster($payload)->json() ?? []);
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function saveItem(array $payload): Response
    {
        return $this->transport()->post('/items/saveItems', $payload);
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function saveItemResult(array $payload): VscuResponseDTO
    {
        return VscuResponseDTO::fromArray($this->saveItem($payload)->json() ?? []);
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function saveItemComposition(array $payload): Response
    {
        return $this->transport()->post('/items/saveItemComposition', $payload);
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function saveItemCompositionResult(array $payload): VscuResponseDTO
    {
        return VscuResponseDTO::fromArray($this->saveItemComposition($payload)->json() ?? []);
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function savePurchase(array $payload): Response
    {
        return $this->transport()->post('/trnsPurchase/savePurchases', $payload);
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function savePurchaseResult(array $payload): VscuResponseDTO
    {
        return VscuResponseDTO::fromArray($this->savePurchase($payload)->json() ?? []);
    }

    public function getPurchaseTransactions(string $tpin, string $bhfId, string $lastReqDt): Response
    {
        return $this->transport()->post('/trnsPurchase/selectTrnsPurchaseSales', [
            'tpin' => $tpin,
            'bhfId' => $bhfId,
            'lastReqDt' => $lastReqDt,
        ]);
    }

    public function getPurchaseTransactionsResult(string $tpin, string $bhfId, string $lastReqDt): VscuResponseDTO
    {
        return VscuResponseDTO::fromArray($this->getPurchaseTransactions($tpin, $bhfId, $lastReqDt)->json() ?? []);
    }

    public function getImportedItems(string $tpin, string $bhfId, string $lastReqDt): Response
    {
        return $this->transport()->post('/imports/selectImportItems', [
            'tpin' => $tpin,
            'bhfId' => $bhfId,
            'lastReqDt' => $lastReqDt,
        ]);
    }

    public function getImportedItemsResult(string $tpin, string $bhfId, string $lastReqDt): VscuResponseDTO
    {
        return VscuResponseDTO::fromArray($this->getImportedItems($tpin, $bhfId, $lastReqDt)->json() ?? []);
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function updateImportedItem(array $payload): Response
    {
        return $this->transport()->post('/imports/updateImportItems', $payload);
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function updateImportedItemResult(array $payload): VscuResponseDTO
    {
        return VscuResponseDTO::fromArray($this->updateImportedItem($payload)->json() ?? []);
    }

    public function getBranches(string $tpin, string $bhfId, string $lastReqDt): Response
    {
        return $this->transport()->post('/branches/selectBranches', [
            'tpin' => $tpin,
            'bhfId' => $bhfId,
            'lastReqDt' => $lastReqDt,
        ]);
    }

    public function getBranchesResult(string $tpin, string $bhfId, string $lastReqDt): VscuResponseDTO
    {
        return VscuResponseDTO::fromArray($this->getBranches($tpin, $bhfId, $lastReqDt)->json() ?? []);
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function saveBranchCustomer(array $payload): Response
    {
        return $this->transport()->post('/branches/saveBrancheCustomers', $payload);
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function saveBranchCustomerResult(array $payload): VscuResponseDTO
    {
        return VscuResponseDTO::fromArray($this->saveBranchCustomer($payload)->json() ?? []);
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function saveBranchUser(array $payload): Response
    {
        return $this->transport()->post('/branches/saveBrancheUsers', $payload);
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function saveBranchUserResult(array $payload): VscuResponseDTO
    {
        return VscuResponseDTO::fromArray($this->saveBranchUser($payload)->json() ?? []);
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function saveBranchInsurance(array $payload): Response
    {
        return $this->transport()->post('/branches/saveBrancheInsurances', $payload);
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function saveBranchInsuranceResult(array $payload): VscuResponseDTO
    {
        return VscuResponseDTO::fromArray($this->saveBranchInsurance($payload)->json() ?? []);
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function getNotices(array $payload): Response
    {
        return $this->transport()->post('/notices/selectNotices', $payload);
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function getNoticesResult(array $payload): VscuResponseDTO
    {
        return VscuResponseDTO::fromArray($this->getNotices($payload)->json() ?? []);
    }

    public function getServerTime(): Response
    {
        return $this->transport()->get('/main/selectServerTime');
    }

    public function getServerTimeResult(): VscuResponseDTO
    {
        return VscuResponseDTO::fromArray($this->getServerTime()->json() ?? []);
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function echoTest(array $payload): Response
    {
        return $this->transport()->post('/test/echoTest', $payload);
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function echoTestResult(array $payload): VscuResponseDTO
    {
        return VscuResponseDTO::fromArray($this->echoTest($payload)->json() ?? []);
    }
}
