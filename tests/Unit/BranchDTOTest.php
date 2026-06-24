<?php

declare(strict_types=1);

use SimiyuSamuel\VscuSdk\DTOs\BranchCustomerDTO;
use SimiyuSamuel\VscuSdk\DTOs\BranchInsuranceDTO;
use SimiyuSamuel\VscuSdk\DTOs\BranchUserDTO;
use SimiyuSamuel\VscuSdk\Exceptions\VscuValidationException;

it('serializes a branch customer payload', function () {
    $customer = BranchCustomerDTO::make([
        'bhfId' => '00',
        'custNo' => 'CUST-001',
        'custTin' => 'P000000000B',
        'custNm' => 'Branch Customer',
        'useYn' => 'Y',
        'tin' => 'P000000000A',
    ]);

    expect($customer->toPayload())->toMatchArray([
        'bhfId' => '00',
        'custNo' => 'CUST-001',
        'custTin' => 'P000000000B',
        'custNm' => 'Branch Customer',
        'useYn' => 'Y',
        'tin' => 'P000000000A',
    ]);
});

it('serializes a branch user payload', function () {
    $user = BranchUserDTO::make([
        'bhfId' => '00',
        'userId' => 'user-001',
        'userNm' => 'Branch User',
        'pwd' => 'secret',
        'useYn' => 'Y',
        'tin' => 'P000000000A',
    ]);

    expect($user->toPayload())->toMatchArray([
        'bhfId' => '00',
        'userId' => 'user-001',
        'userNm' => 'Branch User',
        'pwd' => 'secret',
        'useYn' => 'Y',
        'tin' => 'P000000000A',
    ]);
});

it('serializes a branch insurance payload', function () {
    $insurance = BranchInsuranceDTO::make([
        'bhfId' => '00',
        'isrccCd' => 'INS-001',
        'isrccNm' => 'Insurance One',
        'isrcRt' => 10.5,
        'useYn' => 'Y',
        'tin' => 'P000000000A',
    ]);

    expect($insurance->toPayload())->toMatchArray([
        'bhfId' => '00',
        'isrccCd' => 'INS-001',
        'isrccNm' => 'Insurance One',
        'isrcRt' => 10.5,
        'useYn' => 'Y',
        'tin' => 'P000000000A',
    ]);
});

it('throws when branch customer fields are missing', function () {
    BranchCustomerDTO::make([
        'bhfId' => '00',
    ]);
})->throws(VscuValidationException::class);
