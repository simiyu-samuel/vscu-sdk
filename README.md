# VSCU SDK

Standalone PHP SDK for KRA VSCU invoice payloads.

## Goals

- Build invoices as typed DTOs.
- Serialize to KRA-ready payloads.
- Keep the SDK focused on VSCU invoice flows.
- Make sales and credit-note payloads easy to generate correctly.

## Planned Scope

- Device initialization
- Sales invoices
- Credit notes
- Debit notes
- Receipt payloads
- Payload validation
- Request client for the jar/API layer

## Status

This package is being scaffolded in stages with clean git commits.

## Quick Usage

```php
use SimiyuSamuel\VscuSdk\VscuClient;

$client = new VscuClient(baseUrl: 'http://localhost:8088');

$response = $client->saveSales([
    'invcNo' => 'INV-001',
    'tpin' => 'P000000000A',
    'custTpin' => 'P000000000B',
    'custNm' => 'Test Buyer',
    'rcptTyCd' => 'S',
    'pmtTyCd' => '01',
    'cfmDt' => '2024-01-15',
    'salesDt' => '2024-01-15',
    'totAmt' => 250.00,
    'totTaxblAmt' => 215.52,
    'totTaxAmt' => 34.48,
    'itemList' => [
        [
            'itemSeq' => 1,
            'itemCd' => 'ITEM-001',
            'itemNm' => 'Widget',
            'qty' => 1,
            'prc' => 250.00,
            'taxblAmt' => 215.52,
            'taxAmt' => 34.48,
            'totAmt' => 250.00,
        ],
    ],
]);
```

```php
use SimiyuSamuel\VscuSdk\DTOs\CreditNoteDTO;

$credit = CreditNoteDTO::make([
    'creditNo' => 'CN-001',
    'orgInvcNo' => 'INV-001',
    'tpin' => 'P000000000A',
    'custTpin' => 'P000000000B',
    'cfmDt' => '2024-01-15',
    'salesDt' => '2024-01-15',
    'totAmt' => 250.00,
    'totTaxblAmt' => 215.52,
    'totTaxAmt' => 34.48,
    'itemList' => [],
]);
```
