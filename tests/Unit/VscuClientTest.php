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
