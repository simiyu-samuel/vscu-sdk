<?php

declare(strict_types=1);

use SimiyuSamuel\VscuSdk\DTOs\PurchaseDTO;
use SimiyuSamuel\VscuSdk\Exceptions\VscuValidationException;

it('serializes a purchase payload with supplier and item details', function () {
    $purchase = PurchaseDTO::make([
        'invcNo' => 'PUR-001',
        'spplrTin' => 'P000000000B',
        'spplrNm' => 'Supplier One',
        'spplrInvcNo' => 'SUP-INV-001',
        'pchsTyCd' => 'N',
        'rcptTyCd' => 'P',
        'pchsDt' => '20250930',
        'tin' => 'P000000000A',
        'bhfId' => '00',
        'itemList' => [
            [
                'itemSeq' => 1,
                'itemCd' => 'ITEM-001',
                'itemNm' => 'Widget',
                'qty' => 2,
                'prc' => 500,
                'taxTyCd' => 'B',
            ],
        ],
    ]);

    $payload = $purchase->toPayload();

    expect($payload['spplrTin'])->toBe('P000000000B')
        ->and($payload['itemList'][0]['itemCd'])->toBe('ITEM-001')
        ->and($payload['itemList'][0]['totAmt'])->toBe(1000.0);
});

it('throws when required purchase fields are missing', function () {
    PurchaseDTO::make([
        'spplrTin' => 'P000000000B',
        'spplrNm' => 'Supplier One',
    ]);
})->throws(VscuValidationException::class);
