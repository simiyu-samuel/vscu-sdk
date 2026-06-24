# VSCU SDK

Laravel-ready PHP SDK for the KRA VSCU jar.

This package helps you build and send KRA-compliant payloads for sales, credit notes, debit notes, stock, purchases, imports, branches, notices, and utility lookups. It is designed to sit between your gateway or application and the VSCU jar that communicates with KRA.

## What’s Built

- Typed DTOs for sales invoices, credit notes, debit notes, stock movements, stock master, purchases, imports, and branch payloads.
- A transport layer that talks to the VSCU jar with configurable base URL, timeout, and headers.
- Laravel integration with service provider auto-discovery and a facade.
- Result DTOs for normalizing jar responses.
- Validation that fails early when required fields are missing.
- Tests covering the payload builders and client methods.

## Supported Flows

- Device initialization
- Sales invoices
- Credit notes
- Debit notes
- Stock movements
- Stock master updates
- Item registration
- Item composition
- Purchase transactions
- Import item updates
- Branch customers
- Branch users
- Branch insurance records
- Codes and lookups
- Item classifications
- Customer lookup
- Registered items
- Purchase lookups
- Import lookups
- Branch lookups
- Notices
- Server time
- Test echo

## Installation

```bash
composer require simiyu-samuel/vscu-sdk
```

## Laravel Setup

The package is auto-discovered by Laravel.

Publish the config file if you want to customize the jar URL, timeout, or headers:

```bash
php artisan vendor:publish --tag=vscu-config
```

Example config:

```php
return [
    'base_url' => env('VSCU_BASE_URL', 'http://localhost:8088'),
    'timeout' => (int) env('VSCU_TIMEOUT', 90),
    'headers' => [
        'Accept' => 'application/json',
        // 'Authorization' => 'Bearer your-token',
    ],
];
```

Facade usage:

```php
use Vscu;

Vscu::saveSales([
    'invcNo' => 'INV-001',
    'tpin' => 'P000000000A',
    'custTpin' => 'P000000000B',
    'custNm' => 'Test Buyer',
    'rcptTyCd' => 'S',
    'pmtTyCd' => '01',
    'cfmDt' => '2024-01-15',
    'salesDt' => '2024-01-15',
    'totAmt' => 250.00,
    'itemList' => [],
]);
```

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

Typed helper example:

```php
use SimiyuSamuel\VscuSdk\DTOs\PurchaseDTO;

$client->savePurchase(PurchaseDTO::make([
    'invcNo' => 'PUR-001',
    'spplrTin' => 'P000000000B',
    'spplrNm' => 'Supplier One',
    'spplrInvcNo' => 'SUP-INV-001',
    'pchsTyCd' => 'N',
    'rcptTyCd' => 'P',
    'pchsDt' => '20250930',
    'tin' => 'P000000000A',
    'bhfId' => '00',
    'itemList' => [],
]));
```

## Common Examples

Stock movement:

```php
$client->saveStockMovement([
    'sarNo' => 2,
    'sarTyCd' => '02',
    'ocrnDt' => '20250930',
    'tin' => 'P000000000A',
    'bhfId' => '00',
    'itemList' => [
        [
            'itemSeq' => 1,
            'itemCd' => 'ITEM-001',
            'itemNm' => 'Widget',
            'qty' => 20,
            'prc' => 500.00,
        ],
    ],
]);
```

Stock master:

```php
$client->saveStockMaster([
    'itemCd' => 'ITEM-001',
    'rsdQty' => 25,
    'tin' => 'P000000000A',
    'bhfId' => '00',
]);
```

Branch customer:

```php
$client->saveBranchCustomer([
    'bhfId' => '00',
    'custNo' => 'CUST-001',
    'custTin' => 'P000000000B',
    'custNm' => 'Branch Customer',
    'useYn' => 'Y',
    'tin' => 'P000000000A',
]);
```

## Result Helpers

If you want a normalized response object instead of the raw Laravel response, use the `*Result()` helpers:

```php
$result = $client->saveSalesResult([
    'invcNo' => 'INV-001',
    'tpin' => 'P000000000A',
    'custTpin' => 'P000000000B',
    'rcptTyCd' => 'S',
    'pmtTyCd' => '01',
    'cfmDt' => '2024-01-15',
    'salesDt' => '2024-01-15',
    'totAmt' => 250.00,
    'itemList' => [],
]);

if ($result->isSuccessful()) {
    echo $result->rcptNo;
}
```

## What’s Next

The core SDK is already in place. The next phases will focus on:

- richer typed DTOs for the remaining lookup and utility endpoints
- more response models for jar-specific payloads
- stronger payload normalization helpers
- convenience builders for gateway integrations
- additional examples for Laravel, plain PHP, and SDK consumers

## Development

```bash
composer install
composer test
```

## Notes

- This package targets the VSCU jar as the communication bridge to KRA.
- It can be used from plain PHP or from Laravel.
- The SDK is intentionally focused on payload correctness and reusable request helpers.
