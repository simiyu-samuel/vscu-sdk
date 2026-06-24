<?php

declare(strict_types=1);

return [
    'base_url' => env('VSCU_BASE_URL', 'http://localhost:8088'),
    'timeout' => (int) env('VSCU_TIMEOUT', 90),
];
