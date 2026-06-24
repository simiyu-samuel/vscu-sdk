<?php

declare(strict_types=1);

use SimiyuSamuel\VscuSdk\DTOs\CreditNoteDTO;
use SimiyuSamuel\VscuSdk\DTOs\DebitNoteDTO;

it('serializes a credit note as an R invoice payload', function () {
    $note = CreditNoteDTO::make([
        'creditNo' => 'CN-001',
        'orgInvcNo' => 'INV-001',
        'tpin' => 'P000000000A',
        'custTpin' => 'P000000000B',
        'cfmDt' => '2024-01-15',
        'salesDt' => '2024-01-15',
        'totAmt' => 250.00,
        'totTaxblAmt' => 215.52,
        'totTaxAmt' => 34.48,
    ]);

    $payload = $note->toInvoicePayload();

    expect($payload['rcptTyCd'])->toBe('R')
        ->and($payload['orgInvcNo'])->toBe('INV-001')
        ->and($payload['invcNo'])->toBe('CN-001');
});

it('serializes a debit note as a D invoice payload', function () {
    $note = DebitNoteDTO::make([
        'debitNo' => 'DN-001',
        'orgInvcNo' => 'INV-001',
        'tpin' => 'P000000000A',
        'custTpin' => 'P000000000B',
        'cfmDt' => '2024-01-15',
        'salesDt' => '2024-01-15',
        'totAmt' => 250.00,
        'totTaxblAmt' => 215.52,
        'totTaxAmt' => 34.48,
    ]);

    $payload = $note->toInvoicePayload();

    expect($payload['rcptTyCd'])->toBe('D')
        ->and($payload['orgInvcNo'])->toBe('INV-001')
        ->and($payload['invcNo'])->toBe('DN-001');
});
