<?php

declare(strict_types=1);

namespace SimiyuSamuel\VscuSdk\DTOs;

use SimiyuSamuel\VscuSdk\Exceptions\VscuValidationException;

final class InvoiceLineDTO
{
    public function __construct(
        public readonly int $itemSeq,
        public readonly string $itemCd,
        public readonly string $itemNm,
        public readonly float $qty,
        public readonly float $prc,
        public readonly string $taxTyCd = 'A',
        public readonly ?string $itemClsCd = null,
        public readonly ?string $qtyUnitCd = null,
        public readonly ?string $pkgUnitCd = null,
        public readonly float $pkg = 1.0,
        public readonly ?string $bcd = null,
        public readonly float $splyAmt = 0.0,
        public readonly float $dcRt = 0.0,
        public readonly float $dcAmt = 0.0,
        public readonly float $taxblAmt = 0.0,
        public readonly float $taxAmt = 0.0,
        public readonly float $totAmt = 0.0,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function make(array $data): self
    {
        self::validate($data);

        return new self(
            itemSeq: (int) ($data['itemSeq'] ?? $data['item_number'] ?? 1),
            itemCd: (string) ($data['itemCd'] ?? $data['item_code'] ?? ''),
            itemNm: (string) ($data['itemNm'] ?? $data['item_name'] ?? ''),
            qty: (float) ($data['qty'] ?? $data['quantity'] ?? 0),
            prc: (float) ($data['prc'] ?? $data['unit_price'] ?? 0),
            taxTyCd: (string) ($data['taxTyCd'] ?? $data['tax_type_code'] ?? 'A'),
            itemClsCd: $data['itemClsCd'] ?? $data['item_category'] ?? null,
            qtyUnitCd: $data['qtyUnitCd'] ?? $data['unit_of_measure'] ?? null,
            pkgUnitCd: $data['pkgUnitCd'] ?? null,
            pkg: (float) ($data['pkg'] ?? 1),
            bcd: $data['bcd'] ?? $data['barcode'] ?? null,
            splyAmt: (float) ($data['splyAmt'] ?? 0),
            dcRt: (float) ($data['dcRt'] ?? 0),
            dcAmt: (float) ($data['dcAmt'] ?? 0),
            taxblAmt: (float) ($data['taxblAmt'] ?? 0),
            taxAmt: (float) ($data['taxAmt'] ?? $data['vatAmt'] ?? 0),
            totAmt: (float) ($data['totAmt'] ?? 0),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toPayload(): array
    {
        return array_filter([
            'itemSeq' => $this->itemSeq,
            'itemCd' => $this->itemCd,
            'itemNm' => $this->itemNm,
            'qty' => $this->qty,
            'qtyUnitCd' => $this->qtyUnitCd,
            'prc' => $this->prc,
            'itemClsCd' => $this->itemClsCd,
            'pkgUnitCd' => $this->pkgUnitCd,
            'pkg' => $this->pkg,
            'bcd' => $this->bcd,
            'splyAmt' => $this->splyAmt,
            'dcRt' => $this->dcRt,
            'dcAmt' => $this->dcAmt,
            'taxblAmt' => $this->taxblAmt,
            'taxTyCd' => $this->taxTyCd,
            'taxAmt' => $this->taxAmt,
            'totAmt' => $this->totAmt,
        ], static fn ($value) => $value !== null);
    }

    /**
     * @param array<string, mixed> $data
     */
    private static function validate(array $data): void
    {
        $required = [
            ['itemSeq', 'item_number'],
            ['itemCd', 'item_code'],
            ['itemNm', 'item_name'],
            ['qty', 'quantity'],
            ['prc', 'unit_price'],
        ];

        $missing = [];

        foreach ($required as $group) {
            $present = false;

            foreach ($group as $key) {
                if (array_key_exists($key, $data) && $data[$key] !== '' && $data[$key] !== null) {
                    $present = true;
                    break;
                }
            }

            if (!$present) {
                $missing[] = $group[0];
            }
        }

        if (!empty($missing)) {
            throw new VscuValidationException('Missing required InvoiceLineDTO fields: ' . implode(', ', $missing));
        }
    }
}
