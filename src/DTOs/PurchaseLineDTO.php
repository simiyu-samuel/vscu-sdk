<?php

declare(strict_types=1);

namespace SimiyuSamuel\VscuSdk\DTOs;

use SimiyuSamuel\VscuSdk\Exceptions\VscuValidationException;

final class PurchaseLineDTO
{
    public function __construct(
        public readonly int $itemSeq,
        public readonly string $itemCd,
        public readonly string $itemNm,
        public readonly float $qty,
        public readonly float $prc,
        public readonly string $taxTyCd = 'A',
        public readonly ?string $itemClsCd = null,
        public readonly ?string $bcd = null,
        public readonly ?string $pkgUnitCd = null,
        public readonly float $pkg = 1.0,
        public readonly ?string $qtyUnitCd = null,
        public readonly ?string $itemExprDt = null,
        public readonly ?string $spplrItemClsCd = null,
        public readonly ?string $spplrItemCd = null,
        public readonly ?string $spplrItemNm = null,
        public readonly float $dcRt = 0.0,
        public readonly float $dcAmt = 0.0,
        public readonly float $splyAmt = 0.0,
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

        $qty = (float) ($data['qty'] ?? 0);
        $prc = (float) ($data['prc'] ?? 0);
        $splyAmt = (float) ($data['splyAmt'] ?? round($qty * $prc, 2));
        $dcAmt = (float) ($data['dcAmt'] ?? 0);
        $taxblAmt = (float) ($data['taxblAmt'] ?? round($splyAmt - $dcAmt, 2));
        $taxAmt = (float) ($data['taxAmt'] ?? 0);
        $totAmt = (float) ($data['totAmt'] ?? round($taxblAmt + $taxAmt, 2));

        return new self(
            itemSeq: (int) ($data['itemSeq'] ?? 1),
            itemCd: (string) ($data['itemCd'] ?? ''),
            itemNm: (string) ($data['itemNm'] ?? ''),
            qty: $qty,
            prc: $prc,
            taxTyCd: (string) ($data['taxTyCd'] ?? 'A'),
            itemClsCd: $data['itemClsCd'] ?? null,
            bcd: $data['bcd'] ?? '',
            pkgUnitCd: $data['pkgUnitCd'] ?? null,
            pkg: (float) ($data['pkg'] ?? 1),
            qtyUnitCd: $data['qtyUnitCd'] ?? null,
            itemExprDt: $data['itemExprDt'] ?? null,
            spplrItemClsCd: $data['spplrItemClsCd'] ?? null,
            spplrItemCd: $data['spplrItemCd'] ?? null,
            spplrItemNm: $data['spplrItemNm'] ?? null,
            dcRt: (float) ($data['dcRt'] ?? 0),
            dcAmt: $dcAmt,
            splyAmt: $splyAmt,
            taxblAmt: $taxblAmt,
            taxAmt: $taxAmt,
            totAmt: $totAmt,
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
            'itemClsCd' => $this->itemClsCd,
            'itemNm' => $this->itemNm,
            'bcd' => $this->bcd,
            'spplrItemClsCd' => $this->spplrItemClsCd,
            'spplrItemCd' => $this->spplrItemCd,
            'spplrItemNm' => $this->spplrItemNm,
            'pkgUnitCd' => $this->pkgUnitCd,
            'pkg' => $this->pkg,
            'qtyUnitCd' => $this->qtyUnitCd,
            'qty' => $this->qty,
            'itemExprDt' => $this->itemExprDt,
            'prc' => $this->prc,
            'splyAmt' => $this->splyAmt,
            'dcRt' => $this->dcRt,
            'dcAmt' => $this->dcAmt,
            'taxTyCd' => $this->taxTyCd,
            'taxblAmt' => $this->taxblAmt,
            'taxAmt' => $this->taxAmt,
            'totAmt' => $this->totAmt,
        ], static fn ($value) => $value !== null);
    }

    /**
     * @param array<string, mixed> $data
     */
    private static function validate(array $data): void
    {
        $missing = [];

        foreach (['itemSeq', 'itemCd', 'itemNm', 'qty', 'prc'] as $field) {
            if (!array_key_exists($field, $data) || $data[$field] === '' || $data[$field] === null) {
                $missing[] = $field;
            }
        }

        if (!empty($missing)) {
            throw new VscuValidationException('Missing required PurchaseLineDTO fields: ' . implode(', ', $missing));
        }
    }
}
