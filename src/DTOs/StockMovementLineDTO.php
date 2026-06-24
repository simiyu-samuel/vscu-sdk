<?php

declare(strict_types=1);

namespace SimiyuSamuel\VscuSdk\DTOs;

use SimiyuSamuel\VscuSdk\Exceptions\VscuValidationException;

final class StockMovementLineDTO
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
        public readonly float $splyAmt = 0.0,
        public readonly float $totDcAmt = 0.0,
        public readonly float $taxblAmt = 0.0,
        public readonly float $taxAmt = 0.0,
        public readonly float $totAmt = 0.0,
        public readonly ?int $orgSarNo = null,
        public readonly ?string $regTyCd = null,
        public readonly ?string $custTin = null,
        public readonly ?string $custNm = null,
        public readonly ?string $custBhfId = null,
        public readonly ?string $remark = null,
        public readonly ?string $regrId = null,
        public readonly ?string $regrNm = null,
        public readonly ?string $modrId = null,
        public readonly ?string $modrNm = null,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function make(array $data): self
    {
        self::validate($data);

        return new self(
            itemSeq: (int) ($data['itemSeq'] ?? 1),
            itemCd: (string) ($data['itemCd'] ?? $data['item_code'] ?? ''),
            itemNm: (string) ($data['itemNm'] ?? $data['item_name'] ?? ''),
            qty: (float) ($data['qty'] ?? 0),
            prc: (float) ($data['prc'] ?? 0),
            taxTyCd: (string) ($data['taxTyCd'] ?? 'A'),
            itemClsCd: $data['itemClsCd'] ?? null,
            bcd: $data['bcd'] ?? null,
            pkgUnitCd: $data['pkgUnitCd'] ?? null,
            pkg: (float) ($data['pkg'] ?? 1),
            qtyUnitCd: $data['qtyUnitCd'] ?? null,
            itemExprDt: $data['itemExprDt'] ?? null,
            splyAmt: (float) ($data['splyAmt'] ?? 0),
            totDcAmt: (float) ($data['totDcAmt'] ?? 0),
            taxblAmt: (float) ($data['taxblAmt'] ?? 0),
            taxAmt: (float) ($data['taxAmt'] ?? 0),
            totAmt: (float) ($data['totAmt'] ?? 0),
            orgSarNo: isset($data['orgSarNo']) ? (int) $data['orgSarNo'] : null,
            regTyCd: $data['regTyCd'] ?? null,
            custTin: $data['custTin'] ?? null,
            custNm: $data['custNm'] ?? null,
            custBhfId: $data['custBhfId'] ?? null,
            remark: $data['remark'] ?? null,
            regrId: $data['regrId'] ?? null,
            regrNm: $data['regrNm'] ?? null,
            modrId: $data['modrId'] ?? null,
            modrNm: $data['modrNm'] ?? null,
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
            'pkgUnitCd' => $this->pkgUnitCd,
            'pkg' => $this->pkg,
            'qtyUnitCd' => $this->qtyUnitCd,
            'qty' => $this->qty,
            'itemExprDt' => $this->itemExprDt,
            'prc' => $this->prc,
            'splyAmt' => $this->splyAmt,
            'totDcAmt' => $this->totDcAmt,
            'taxTyCd' => $this->taxTyCd,
            'taxblAmt' => $this->taxblAmt,
            'taxAmt' => $this->taxAmt,
            'totAmt' => $this->totAmt,
            'orgSarNo' => $this->orgSarNo,
            'regTyCd' => $this->regTyCd,
            'custTin' => $this->custTin,
            'custNm' => $this->custNm,
            'custBhfId' => $this->custBhfId,
            'remark' => $this->remark,
            'regrId' => $this->regrId,
            'regrNm' => $this->regrNm,
            'modrId' => $this->modrId,
            'modrNm' => $this->modrNm,
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
            throw new VscuValidationException('Missing required StockMovementLineDTO fields: ' . implode(', ', $missing));
        }
    }
}
