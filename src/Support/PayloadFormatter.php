<?php

declare(strict_types=1);

namespace SimiyuSamuel\VscuSdk\Support;

final class PayloadFormatter
{
    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    public static function format(array $payload): array
    {
        $numericFields = [
            'totAmt', 'totTaxAmt', 'totTaxblAmt', 'taxAmt', 'taxblAmt', 'splyAmt',
            'taxAmtA', 'taxAmtB', 'taxAmtC', 'taxAmtD', 'taxAmtE',
            'taxblAmtA', 'taxblAmtB', 'taxblAmtC', 'taxblAmtD', 'taxblAmtE',
            'dcAmt', 'totDcAmt', 'prc', 'qty', 'pkg', 'taxRtA', 'taxRtB', 'taxRtC', 'taxRtD', 'taxRtE',
        ];

        foreach ($payload as $key => $value) {
            if (is_array($value)) {
                $payload[$key] = self::format($value);
                continue;
            }

            if (in_array($key, $numericFields, true) && is_numeric($value)) {
                $payload[$key] = round((float) $value, 2);
            }
        }

        return $payload;
    }
}
