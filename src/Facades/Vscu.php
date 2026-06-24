<?php

declare(strict_types=1);

namespace SimiyuSamuel\VscuSdk\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Http\Client\Response initializeDevice(array|\SimiyuSamuel\VscuSdk\DTOs\DeviceInitDTO $payload)
 * @method static \Illuminate\Http\Client\Response saveSales(array|\SimiyuSamuel\VscuSdk\DTOs\InvoiceDTO $payload)
 * @method static \Illuminate\Http\Client\Response saveCreditNote(array|\SimiyuSamuel\VscuSdk\DTOs\CreditNoteDTO $payload)
 * @method static \Illuminate\Http\Client\Response saveDebitNote(array|\SimiyuSamuel\VscuSdk\DTOs\DebitNoteDTO $payload)
 * @method static \Illuminate\Http\Client\Response saveStockMovement(array|\SimiyuSamuel\VscuSdk\DTOs\StockMovementDTO $payload)
 * @method static \Illuminate\Http\Client\Response getStockMovements(string $tpin, string $bhfId, string $lastReqDt)
 * @method static \Illuminate\Http\Client\Response saveStockMaster(array|\SimiyuSamuel\VscuSdk\DTOs\StockMasterDTO $payload)
 * @method static \Illuminate\Http\Client\Response saveItem(array $payload)
 * @method static \Illuminate\Http\Client\Response saveItemComposition(array $payload)
 * @method static \Illuminate\Http\Client\Response savePurchase(array $payload)
 * @method static \Illuminate\Http\Client\Response getPurchaseTransactions(string $tpin, string $bhfId, string $lastReqDt)
 * @method static \Illuminate\Http\Client\Response getImportedItems(string $tpin, string $bhfId, string $lastReqDt)
 * @method static \Illuminate\Http\Client\Response updateImportedItem(array $payload)
 * @method static \Illuminate\Http\Client\Response getBranches(string $tpin, string $bhfId, string $lastReqDt)
 * @method static \Illuminate\Http\Client\Response saveBranchCustomer(array $payload)
 * @method static \Illuminate\Http\Client\Response saveBranchUser(array $payload)
 * @method static \Illuminate\Http\Client\Response saveBranchInsurance(array $payload)
 * @method static \Illuminate\Http\Client\Response getNotices(array $payload)
 * @method static \Illuminate\Http\Client\Response getServerTime()
 * @method static \Illuminate\Http\Client\Response echoTest(array $payload)
 */
final class Vscu extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'vscu';
    }
}
