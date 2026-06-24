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
        'http://localhost:8088/code/selectCodes*' => Http::response([
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
        return str_contains($request->url(), '/code/selectCodes')
            && $request['tpin'] === 'P000000000A'
            && $request['bhfId'] === '00'
            && $request['lastReqDt'] === '20240101000000';
    });
});
