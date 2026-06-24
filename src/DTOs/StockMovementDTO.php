<?php

declare(strict_types=1);

namespace SimiyuSamuel\VscuSdk\DTOs;

use SimiyuSamuel\VscuSdk\Exceptions\VscuValidationException;

final class StockMovementDTO
{
    /**
     * @param StockMovementLineDTO[] $itemList
     */
    public function __construct(
        public readonly int $sarNo,
        public readonly string $sarTyCd,
        public readonly string $ocrnDt,
        public readonly string $tin,
        public readonly string $bhfId,
        public readonly int $totItemCnt = 0,
        public readonly float $totTaxblAmt = 0.0,
        public readonly float $totTaxAmt = 0.0,
        public readonly float $totAmt = 0.0,
        public readonly int $orgSarNo = 0,
        public readonly string $regTyCd = 'M',
        public readonly ?string $custTin = null,
        public readonly ?string $custNm = null,
        public readonly ?string $custBhfId = null,
        public readonly ?string $remark = null,
        public readonly ?string $regrId = null,
        public readonly ?string $regrNm = null,
        public readonly ?string $modrId = null,
        public readonly ?string $modrNm = null,
        public readonly array $itemList = [],
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function make(array $data): self
    {
        self::validate($data);

        $items = $data['itemList'] ?? [];
        $items = array_map(
            fn (mixed $item) => $item instanceof StockMovementLineDTO ? $item : StockMovementLineDTO::make((array) $item),
            is_array($items) ? $items : []
        );

        return new self(
            sarNo: (int) ($data['sarNo'] ?? 0),
            sarTyCd: (string) ($data['sarTyCd'] ?? ''),
            ocrnDt: (string) ($data['ocrnDt'] ?? ''),
            tin: (string) ($data['tin'] ?? $data['tpin'] ?? ''),
            bhfId: (string) ($data['bhfId'] ?? ''),
            totItemCnt: (int) ($data['totItemCnt'] ?? count($items)),
            totTaxblAmt: (float) ($data['totTaxblAmt'] ?? 0),
            totTaxAmt: (float) ($data['totTaxAmt'] ?? 0),
            totAmt: (float) ($data['totAmt'] ?? 0),
            orgSarNo: (int) ($data['orgSarNo'] ?? 0),
            regTyCd: (string) ($data['regTyCd'] ?? 'M'),
            custTin: $data['custTin'] ?? null,
            custNm: $data['custNm'] ?? null,
            custBhfId: $data['custBhfId'] ?? null,
            remark: $data['remark'] ?? null,
            regrId: $data['regrId'] ?? null,
            regrNm: $data['regrNm'] ?? null,
            modrId: $data['modrId'] ?? null,
            modrNm: $data['modrNm'] ?? null,
            itemList: $items,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toPayload(): array
    {
        return array_filter([
            'sarNo' => $this->sarNo,
            'sarTyCd' => $this->sarTyCd,
            'ocrnDt' => $this->ocrnDt,
            'tin' => $this->tin,
            'bhfId' => $this->bhfId,
            'totItemCnt' => $this->totItemCnt,
            'totTaxblAmt' => $this->totTaxblAmt,
            'totTaxAmt' => $this->totTaxAmt,
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
            'itemList' => array_map(fn (StockMovementLineDTO $item) => $item->toPayload(), $this->itemList),
        ], static fn ($value) => $value !== null);
    }

    /**
     * @param array<string, mixed> $data
     */
    private static function validate(array $data): void
    {
        $missing = [];

        foreach (['sarNo', 'sarTyCd', 'ocrnDt', 'tin', 'bhfId'] as $field) {
            if (!array_key_exists($field, $data) || $data[$field] === '' || $data[$field] === null) {
                $missing[] = $field;
            }
        }

        if (!empty($missing)) {
            throw new VscuValidationException('Missing required StockMovementDTO fields: ' . implode(', ', $missing));
        }
    }
}
