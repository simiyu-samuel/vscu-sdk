<?php

declare(strict_types=1);

use SimiyuSamuel\VscuSdk\Support\PayloadFormatter;

it('rounds nested numeric values in payloads', function () {
    $payload = PayloadFormatter::format([
        'totAmt' => 250,
        'itemList' => [
            [
                'qty' => 1,
                'prc' => 250.129,
                'taxAmt' => 34.485,
            ],
        ],
    ]);

    expect($payload['totAmt'])->toBe(250.0)
        ->and($payload['itemList'][0]['prc'])->toBe(250.13)
        ->and($payload['itemList'][0]['taxAmt'])->toBe(34.49);
});
