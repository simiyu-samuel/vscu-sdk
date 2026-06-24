<?php

declare(strict_types=1);

use SimiyuSamuel\VscuSdk\DTOs\ImportItemUpdateDTO;
use SimiyuSamuel\VscuSdk\Exceptions\VscuValidationException;

it('serializes an import update payload', function () {
    $import = ImportItemUpdateDTO::make([
        'taskCd' => 'UPDATE',
        'dclDe' => '20250930',
        'itemSeq' => 1,
        'hsCd' => '0101',
        'itemCd' => 'ITEM-001',
        'itemClsCd' => '10101501',
        'imptItemSttsCd' => '3',
        'tin' => 'P000000000A',
        'bhfId' => '00',
    ]);

    expect($import->toPayload())->toMatchArray([
        'taskCd' => 'UPDATE',
        'dclDe' => '20250930',
        'itemSeq' => 1,
        'hsCd' => '0101',
        'itemCd' => 'ITEM-001',
        'itemClsCd' => '10101501',
        'imptItemSttsCd' => '3',
        'tin' => 'P000000000A',
        'bhfId' => '00',
    ]);
});

it('throws when import update fields are missing', function () {
    ImportItemUpdateDTO::make([
        'taskCd' => 'UPDATE',
    ]);
})->throws(VscuValidationException::class);
