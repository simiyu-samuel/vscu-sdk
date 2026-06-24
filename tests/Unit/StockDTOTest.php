<?php

declare(strict_types=1);

use SimiyuSamuel\VscuSdk\DTOs\StockMasterDTO;
use SimiyuSamuel\VscuSdk\DTOs\StockMovementDTO;
use SimiyuSamuel\VscuSdk\Exceptions\VscuValidationException;

it('serializes a stock movement payload with full item details', function () {
    $movement = StockMovementDTO::make([
        'sarNo' => 2,
        'sarTyCd' => '02',
        'ocrnDt' => '20250930',
        'tin' => 'P000000000A',
        'bhfId' => '00',
        'custTin' => 'P000000000B',
        'custNm' => 'Buyer One',
        'itemList' => [
            [
                'itemSeq' => 1,
                'itemCd' => 'ITEM-001',
                'itemClsCd' => '10101501',
                'itemNm' => 'Widget',
                'qty' => 20,
                'prc' => 500,
                'splyAmt' => 10000,
                'taxblAmt' => 10000,
                'taxAmt' => 0,
                'totAmt' => 10000,
            ],
        ],
    ]);

    $payload = $movement->toPayload();

    expect($payload['tin'])->toBe('P000000000A')
        ->and($payload['itemList'][0])->toHaveKeys([
            'itemSeq',
            'itemCd',
            'itemClsCd',
            'itemNm',
            'qty',
            'prc',
            'splyAmt',
            'taxblAmt',
            'taxAmt',
            'totAmt',
        ]);
});

it('serializes a stock master payload', function () {
    $master = StockMasterDTO::make([
        'itemCd' => 'ITEM-001',
        'rsdQty' => 25,
        'tin' => 'P000000000A',
        'bhfId' => '00',
    ]);

    expect($master->toPayload())->toMatchArray([
        'itemCd' => 'ITEM-001',
        'rsdQty' => 25.0,
        'tin' => 'P000000000A',
        'bhfId' => '00',
    ]);
});

it('throws when stock master fields are missing', function () {
    StockMasterDTO::make([
        'itemCd' => 'ITEM-001',
        'tin' => 'P000000000A',
    ]);
})->throws(VscuValidationException::class);
