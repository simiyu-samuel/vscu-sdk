<?php

declare(strict_types=1);

use SimiyuSamuel\VscuSdk\Facades\Vscu;
use SimiyuSamuel\VscuSdk\VscuClient;

it('binds the vscu client into the container', function () {
    expect(app('vscu'))->toBeInstanceOf(VscuClient::class);
});

it('resolves the facade to the vscu client', function () {
    expect(Vscu::getFacadeRoot())->toBeInstanceOf(VscuClient::class);
});
