<?php

declare(strict_types=1);

namespace SimiyuSamuel\VscuSdk\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Http\Client\Response initializeDevice(array|\SimiyuSamuel\VscuSdk\DTOs\DeviceInitDTO $payload)
 * @method static \Illuminate\Http\Client\Response saveSales(array|\SimiyuSamuel\VscuSdk\DTOs\InvoiceDTO $payload)
 * @method static \Illuminate\Http\Client\Response saveCreditNote(array|\SimiyuSamuel\VscuSdk\DTOs\CreditNoteDTO $payload)
 * @method static \Illuminate\Http\Client\Response saveDebitNote(array|\SimiyuSamuel\VscuSdk\DTOs\DebitNoteDTO $payload)
 */
final class Vscu extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'vscu';
    }
}
