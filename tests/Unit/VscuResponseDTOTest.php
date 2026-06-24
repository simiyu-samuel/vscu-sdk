<?php

declare(strict_types=1);

use SimiyuSamuel\VscuSdk\DTOs\VscuResponseDTO;

it('parses a successful kra response into a typed dto', function () {
    $response = VscuResponseDTO::fromArray([
        'resultCd' => '000',
        'resultMsg' => 'Successful',
        'data' => [
            'rcptNo' => 'RCPT-001',
            'intrlData' => 'INTRL-123',
            'rcptSign' => 'SIGN-456',
        ],
    ]);

    expect($response->isSuccessful())->toBeTrue()
        ->and($response->rcptNo)->toBe('RCPT-001')
        ->and($response->intrlData)->toBe('INTRL-123')
        ->and($response->rcptSign)->toBe('SIGN-456');
});

it('treats non-success result codes as failures', function () {
    $response = VscuResponseDTO::fromArray([
        'resultCd' => '101',
        'resultMsg' => 'Rejected',
        'data' => [],
    ]);

    expect($response->isSuccessful())->toBeFalse();
});
