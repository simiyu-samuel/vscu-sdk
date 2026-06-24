<?php

declare(strict_types=1);

use SimiyuSamuel\VscuSdk\DTOs\InvoiceDTO;
use SimiyuSamuel\VscuSdk\DTOs\InvoiceLineDTO;
use SimiyuSamuel\VscuSdk\Exceptions\VscuValidationException;

it('serializes a full sales invoice payload', function () {
    $invoice = InvoiceDTO::make([
        'invcNo' => 'INV-001',
        'tpin' => 'P000000000A',
        'custTpin' => 'P000000000B',
        'custNm' => 'Test Buyer',
        'rcptTyCd' => 'S',
        'pmtTyCd' => '01',
        'cfmDt' => '2024-01-15',
        'salesDt' => '2024-01-15',
        'totAmt' => 11600.00,
        'totTaxblAmt' => 10000.00,
        'totTaxAmt' => 1600.00,
        'taxblAmtB' => 10000.00,
        'taxRtB' => 16,
        'taxAmtB' => 1600.00,
        'itemList' => [
            [
                'itemSeq' => 1,
                'itemCd' => 'ITEM-001',
                'itemNm' => 'Test Widget',
                'qty' => 2,
                'prc' => 5000.00,
                'qtyUnitCd' => 'EA',
                'itemClsCd' => '10101501',
                'splyAmt' => 10000.00,
                'taxblAmt' => 10000.00,
                'taxAmt' => 1600.00,
                'totAmt' => 11600.00,
                'taxTyCd' => 'A',
            ],
        ],
    ]);

    $payload = $invoice->toPayload();

    expect($payload['rcptTyCd'])->toBe('S')
        ->and($payload['itemList'][0]['itemCd'])->toBe('ITEM-001')
        ->and($payload['itemList'][0]['taxAmt'])->toBe(1600.00)
        ->and($payload['receipt'])->toHaveKeys(['custTin', 'custNm', 'prchrAcptcYn', 'topMsg', 'btmMsg']);
});

it('normalizes invoice line items to payload arrays', function () {
    $line = InvoiceLineDTO::make([
        'itemSeq' => 1,
        'itemCd' => 'ITEM-123',
        'itemNm' => 'Widget',
        'qty' => 1,
        'prc' => 250.00,
        'taxblAmt' => 215.52,
        'taxAmt' => 34.48,
        'totAmt' => 250.00,
        'taxTyCd' => 'A',
    ]);

    expect($line->toPayload())->toMatchArray([
        'itemSeq' => 1,
        'itemCd' => 'ITEM-123',
        'taxAmt' => 34.48,
        'totAmt' => 250.00,
    ]);
});

it('throws when required invoice fields are missing', function () {
    InvoiceDTO::make([
        'tpin' => 'P000000000A',
        'custTpin' => 'P000000000B',
        'rcptTyCd' => 'S',
        'pmtTyCd' => '01',
    ]);
})->throws(VscuValidationException::class);
