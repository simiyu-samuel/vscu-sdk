# VSCU SDK

Standalone PHP SDK for KRA VSCU invoice payloads.

## Goals

- Build invoices as typed DTOs.
- Serialize to KRA-ready payloads.
- Keep the SDK focused on VSCU invoice flows.
- Make sales and credit-note payloads easy to generate correctly.
- Include the jar transport layer used to reach KRA.
- Cover stock, stock master, and lookup flows too.

## Planned Scope

- Device initialization
- Codes and lookups
- Item classifications
- Customer lookup
- Registered items
- Purchases
- Imports
- Branch management helpers
- Notices
- Server time and test echo utilities
- Sales invoices
- Credit notes
- Debit notes
- Stock movements
- Stock master updates
- Receipt payloads
- Payload validation
- Request client for the jar/API layer

## Status

This package is being scaffolded in stages with clean git commits.

## Laravel Integration

Publish the config file:

```bash
php artisan vendor:publish --tag=vscu-config
```

Then use the facade:

```php
use Vscu;

Vscu::saveSales([
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
```

You can also override jar headers in `config/vscu.php` when the jar expects auth or custom metadata:

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

Stock movement example:

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

Stock master update example:

```php
$client->saveStockMaster([
    'itemCd' => 'ITEM-001',
    'rsdQty' => 25,
    'tin' => 'P000000000A',
    'bhfId' => '00',
]);
```

Typed helpers are available for the new write-side payloads too:

```php
use SimiyuSamuel\VscuSdk\DTOs\PurchaseDTO;
use SimiyuSamuel\VscuSdk\DTOs\BranchCustomerDTO;

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

$client->saveBranchCustomer(BranchCustomerDTO::make([
    'bhfId' => '00',
    'custNo' => 'CUST-001',
    'custTin' => 'P000000000B',
    'custNm' => 'Branch Customer',
    'useYn' => 'Y',
    'tin' => 'P000000000A',
]));
```

Other jar helpers are also available for purchase, branch, import, notice, and utility endpoints:

```php
$client->savePurchase([...]);
$client->getBranches('P000000000A', '00', '20240101000000');
$client->getServerTime();
```

Typed result helpers are also available when you want a normalized SDK response:

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
