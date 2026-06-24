<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use SimiyuSamuel\VscuSdk\VscuClient;

it('posts sales invoices to the VSCU sales endpoint', function () {
    Http::fake([
        'http://localhost:8088/trnsSales/saveSales' => Http::response([
            'resultCd' => '000',
            'resultMsg' => 'Successful',
            'data' => [],
        ], 200),
    ]);

    $client = new VscuClient();
    $client->saveSales([
        'invcNo' => 'INV-001',
        'tpin' => 'P000000000A',
        'custTpin' => 'P000000000B',
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

    Http::assertSent(function ($request) {
        return $request->url() === 'http://localhost:8088/trnsSales/saveSales'
            && $request['invcNo'] === 'INV-001'
            && $request['itemList'][0]['taxAmt'] === 34.48;
    });
});

it('returns a typed sales result dto', function () {
    Http::fake([
        'http://localhost:8088/trnsSales/saveSales' => Http::response([
            'resultCd' => '000',
            'resultMsg' => 'Successful',
            'data' => [
                'rcptNo' => 'RCPT-123',
                'intrlData' => 'INTRL-ABC',
                'rcptSign' => 'SIGN-XYZ',
            ],
        ], 200),
    ]);

    $client = new VscuClient();
    $result = $client->saveSalesResult([
        'invcNo' => 'INV-002',
        'tpin' => 'P000000000A',
        'custTpin' => 'P000000000B',
        'rcptTyCd' => 'S',
        'pmtTyCd' => '01',
        'cfmDt' => '2024-01-15',
        'salesDt' => '2024-01-15',
        'totAmt' => 250.00,
        'itemList' => [],
    ]);

    expect($result->isSuccessful())->toBeTrue()
        ->and($result->rcptNo)->toBe('RCPT-123')
        ->and($result->intrlData)->toBe('INTRL-ABC');
});

it('fetches codes through the lookup endpoint', function () {
    Http::fake([
        'http://localhost:8088/code/selectCodes' => Http::response([
            'resultCd' => '000',
            'resultMsg' => 'Successful',
            'data' => [
                'codeList' => [],
            ],
        ], 200),
    ]);

    $client = new VscuClient();
    $result = $client->getCodesResult('P000000000A', '00', '20240101000000');

    expect($result->isSuccessful())->toBeTrue();

    Http::assertSent(function ($request) {
        return $request->url() === 'http://localhost:8088/code/selectCodes'
            && $request->method() === 'POST'
            && $request['tpin'] === 'P000000000A'
            && $request['bhfId'] === '00'
            && $request['lastReqDt'] === '20240101000000';
    });
});

it('posts stock movements to the stock endpoint', function () {
    Http::fake([
        'http://localhost:8088/stock/saveStockItems' => Http::response([
            'resultCd' => '000',
            'resultMsg' => 'Successful',
            'data' => [],
        ], 200),
    ]);

    $client = new VscuClient();
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
                'prc' => 500,
            ],
        ],
    ]);

    Http::assertSentCount(1);
    Http::assertSent(function ($request) {
        return $request->url() === 'http://localhost:8088/stock/saveStockItems'
            && $request->method() === 'POST';
    });
});

it('posts stock master updates to the stock master endpoint', function () {
    Http::fake([
        'http://localhost:8088/stockMaster/saveStockMaster' => Http::response([
            'resultCd' => '000',
            'resultMsg' => 'Successful',
            'data' => [],
        ], 200),
    ]);

    $client = new VscuClient();
    $client->saveStockMaster([
        'itemCd' => 'ITEM-001',
        'rsdQty' => 25,
        'tin' => 'P000000000A',
        'bhfId' => '00',
    ]);

    Http::assertSentCount(1);
    Http::assertSent(function ($request) {
        return $request->url() === 'http://localhost:8088/stockMaster/saveStockMaster'
            && $request->method() === 'POST';
    });
});

it('uses the jar customer lookup field name', function () {
    Http::fake([
        'http://localhost:8088/customers/selectCustomer' => Http::response([
            'resultCd' => '000',
            'resultMsg' => 'Successful',
            'data' => [],
        ], 200),
    ]);

    $client = new VscuClient();
    $client->getCustomerByPin('P000000000A', '00', 'P000000000B');

    Http::assertSent(function ($request) {
        return $request->url() === 'http://localhost:8088/customers/selectCustomer'
            && $request->method() === 'POST'
            && $request['custmTin'] === 'P000000000B';
    });
});

it('posts purchases to the purchase endpoint', function () {
    Http::fake([
        'http://localhost:8088/trnsPurchase/savePurchases' => Http::response([
            'resultCd' => '000',
            'resultMsg' => 'Successful',
            'data' => [],
        ], 200),
    ]);

    $client = new VscuClient();
    $client->savePurchase([
        'purchaseNo' => 'PUR-001',
        'tpin' => 'P000000000A',
    ]);

    Http::assertSentCount(1);
    Http::assertSent(fn ($request) => $request->url() === 'http://localhost:8088/trnsPurchase/savePurchases');
});

it('posts item payloads to the item endpoint', function () {
    Http::fake([
        'http://localhost:8088/items/saveItems' => Http::response([
            'resultCd' => '000',
            'resultMsg' => 'Successful',
            'data' => [],
        ], 200),
    ]);

    $client = new VscuClient();
    $client->saveItem([
        'itemCd' => 'ITEM-001',
        'itemNm' => 'Widget',
    ]);

    Http::assertSentCount(1);
    Http::assertSent(fn ($request) => $request->url() === 'http://localhost:8088/items/saveItems');
});

it('fetches branches through the branch lookup endpoint', function () {
    Http::fake([
        'http://localhost:8088/branches/selectBranches' => Http::response([
            'resultCd' => '000',
            'resultMsg' => 'Successful',
            'data' => [],
        ], 200),
    ]);

    $client = new VscuClient();
    $client->getBranches('P000000000A', '00', '20240101000000');

    Http::assertSentCount(1);
    Http::assertSent(fn ($request) => $request->url() === 'http://localhost:8088/branches/selectBranches');
});

it('fetches the server time using the jar utility endpoint', function () {
    Http::fake([
        'http://localhost:8088/main/selectServerTime' => Http::response([
            'resultCd' => '000',
            'resultMsg' => 'Successful',
            'data' => [],
        ], 200),
    ]);

    $client = new VscuClient();
    $client->getServerTime();

    Http::assertSentCount(1);
    Http::assertSent(fn ($request) => $request->url() === 'http://localhost:8088/main/selectServerTime'
        && $request->method() === 'GET');
});
