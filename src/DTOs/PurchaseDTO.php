<?php

declare(strict_types=1);

namespace SimiyuSamuel\VscuSdk\DTOs;

use SimiyuSamuel\VscuSdk\Exceptions\VscuValidationException;

final class PurchaseDTO
{
    /**
     * @param PurchaseLineDTO[] $itemList
     */
    public function __construct(
        public readonly string $invcNo,
        public readonly string $spplrTin,
        public readonly string $spplrNm,
        public readonly string $spplrInvcNo,
        public readonly string $pchsTyCd,
        public readonly string $rcptTyCd,
        public readonly string $pchsDt,
        public readonly string $tin,
        public readonly string $bhfId,
        public readonly string $regTyCd = 'A',
        public readonly string $pmtTyCd = '01',
        public readonly string $pchsSttsCd = '02',
        public readonly string $spplrBhfId = '00',
        public readonly ?string $orgInvcNo = null,
        public readonly ?string $wrhsDt = null,
        public readonly ?string $cnclReqDt = null,
        public readonly ?string $cnclDt = null,
        public readonly ?string $rfdDt = null,
        public readonly ?string $remark = null,
        public readonly ?string $regrNm = null,
        public readonly ?string $regrId = null,
        public readonly ?string $modrNm = null,
        public readonly ?string $modrId = null,
        public readonly int $totItemCnt = 0,
        public readonly float $taxblAmtA = 0.0,
        public readonly float $taxblAmtB = 0.0,
        public readonly float $taxblAmtC = 0.0,
        public readonly float $taxblAmtD = 0.0,
        public readonly float $taxblAmtE = 0.0,
        public readonly float $taxRtA = 0.0,
        public readonly float $taxRtB = 0.0,
        public readonly float $taxRtC = 0.0,
        public readonly float $taxRtD = 0.0,
        public readonly float $taxRtE = 0.0,
        public readonly float $taxAmtA = 0.0,
        public readonly float $taxAmtB = 0.0,
        public readonly float $taxAmtC = 0.0,
        public readonly float $taxAmtD = 0.0,
        public readonly float $taxAmtE = 0.0,
        public readonly float $totTaxblAmt = 0.0,
        public readonly float $totTaxAmt = 0.0,
        public readonly float $totAmt = 0.0,
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
            fn (mixed $item) => $item instanceof PurchaseLineDTO ? $item : PurchaseLineDTO::make((array) $item),
            is_array($items) ? $items : []
        );

        $totals = self::sumBuckets($items);

        return new self(
            invcNo: (string) ($data['invcNo'] ?? ''),
            spplrTin: (string) ($data['spplrTin'] ?? ''),
            spplrNm: (string) ($data['spplrNm'] ?? ''),
            spplrInvcNo: (string) ($data['spplrInvcNo'] ?? ''),
            pchsTyCd: (string) ($data['pchsTyCd'] ?? 'N'),
            rcptTyCd: (string) ($data['rcptTyCd'] ?? 'P'),
            pchsDt: (string) ($data['pchsDt'] ?? ''),
            tin: (string) ($data['tin'] ?? $data['tpin'] ?? ''),
            bhfId: (string) ($data['bhfId'] ?? ''),
            regTyCd: (string) ($data['regTyCd'] ?? 'A'),
            pmtTyCd: (string) ($data['pmtTyCd'] ?? '01'),
            pchsSttsCd: (string) ($data['pchsSttsCd'] ?? '02'),
            spplrBhfId: (string) ($data['spplrBhfId'] ?? '00'),
            orgInvcNo: $data['orgInvcNo'] ?? null,
            wrhsDt: $data['wrhsDt'] ?? null,
            cnclReqDt: $data['cnclReqDt'] ?? null,
            cnclDt: $data['cnclDt'] ?? null,
            rfdDt: $data['rfdDt'] ?? null,
            remark: $data['remark'] ?? null,
            regrNm: $data['regrNm'] ?? null,
            regrId: $data['regrId'] ?? null,
            modrNm: $data['modrNm'] ?? null,
            modrId: $data['modrId'] ?? null,
            totItemCnt: (int) ($data['totItemCnt'] ?? count($items)),
            taxblAmtA: (float) ($data['taxblAmtA'] ?? $totals['taxblAmtA']),
            taxblAmtB: (float) ($data['taxblAmtB'] ?? $totals['taxblAmtB']),
            taxblAmtC: (float) ($data['taxblAmtC'] ?? $totals['taxblAmtC']),
            taxblAmtD: (float) ($data['taxblAmtD'] ?? $totals['taxblAmtD']),
            taxblAmtE: (float) ($data['taxblAmtE'] ?? $totals['taxblAmtE']),
            taxRtA: (float) ($data['taxRtA'] ?? 0),
            taxRtB: (float) ($data['taxRtB'] ?? 0),
            taxRtC: (float) ($data['taxRtC'] ?? 0),
            taxRtD: (float) ($data['taxRtD'] ?? 0),
            taxRtE: (float) ($data['taxRtE'] ?? 0),
            taxAmtA: (float) ($data['taxAmtA'] ?? $totals['taxAmtA']),
            taxAmtB: (float) ($data['taxAmtB'] ?? $totals['taxAmtB']),
            taxAmtC: (float) ($data['taxAmtC'] ?? $totals['taxAmtC']),
            taxAmtD: (float) ($data['taxAmtD'] ?? $totals['taxAmtD']),
            taxAmtE: (float) ($data['taxAmtE'] ?? $totals['taxAmtE']),
            totTaxblAmt: (float) ($data['totTaxblAmt'] ?? array_sum($totals['taxable'])),
            totTaxAmt: (float) ($data['totTaxAmt'] ?? array_sum($totals['tax'])),
            totAmt: (float) ($data['totAmt'] ?? array_sum($totals['total'])),
            itemList: $items,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toPayload(): array
    {
        return array_filter([
            'invcNo' => $this->invcNo,
            'orgInvcNo' => $this->orgInvcNo,
            'spplrTin' => $this->spplrTin,
            'spplrNm' => $this->spplrNm,
            'spplrInvcNo' => $this->spplrInvcNo,
            'pchsTyCd' => $this->pchsTyCd,
            'rcptTyCd' => $this->rcptTyCd,
            'pchsDt' => $this->pchsDt,
            'tin' => $this->tin,
            'bhfId' => $this->bhfId,
            'regTyCd' => $this->regTyCd,
            'pmtTyCd' => $this->pmtTyCd,
            'pchsSttsCd' => $this->pchsSttsCd,
            'spplrBhfId' => $this->spplrBhfId,
            'wrhsDt' => $this->wrhsDt,
            'cnclReqDt' => $this->cnclReqDt,
            'cnclDt' => $this->cnclDt,
            'rfdDt' => $this->rfdDt,
            'remark' => $this->remark,
            'regrNm' => $this->regrNm,
            'regrId' => $this->regrId,
            'modrNm' => $this->modrNm,
            'modrId' => $this->modrId,
            'totItemCnt' => $this->totItemCnt,
            'taxblAmtA' => $this->taxblAmtA,
            'taxblAmtB' => $this->taxblAmtB,
            'taxblAmtC' => $this->taxblAmtC,
            'taxblAmtD' => $this->taxblAmtD,
            'taxblAmtE' => $this->taxblAmtE,
            'taxRtA' => $this->taxRtA,
            'taxRtB' => $this->taxRtB,
            'taxRtC' => $this->taxRtC,
            'taxRtD' => $this->taxRtD,
            'taxRtE' => $this->taxRtE,
            'taxAmtA' => $this->taxAmtA,
            'taxAmtB' => $this->taxAmtB,
            'taxAmtC' => $this->taxAmtC,
            'taxAmtD' => $this->taxAmtD,
            'taxAmtE' => $this->taxAmtE,
            'totTaxblAmt' => $this->totTaxblAmt,
            'totTaxAmt' => $this->totTaxAmt,
            'totAmt' => $this->totAmt,
            'itemList' => array_map(fn (PurchaseLineDTO $item) => $item->toPayload(), $this->itemList),
        ], static fn ($value) => $value !== null);
    }

    /**
     * @param array<string, mixed> $data
     */
    private static function validate(array $data): void
    {
        $missing = [];

        foreach (['invcNo', 'spplrTin', 'spplrNm', 'spplrInvcNo', 'pchsTyCd', 'rcptTyCd', 'pchsDt'] as $field) {
            if (!array_key_exists($field, $data) || $data[$field] === '' || $data[$field] === null) {
                $missing[] = $field;
            }
        }

        if (!empty($missing)) {
            throw new VscuValidationException('Missing required PurchaseDTO fields: ' . implode(', ', $missing));
        }
    }

    /**
     * @param PurchaseLineDTO[] $items
     * @return array{taxable:array<string,float>,tax:array<string,float>,total:array<string,float>,taxblAmtA:float,taxblAmtB:float,taxblAmtC:float,taxblAmtD:float,taxblAmtE:float,taxAmtA:float,taxAmtB:float,taxAmtC:float,taxAmtD:float,taxAmtE:float}
     */
    private static function sumBuckets(array $items): array
    {
        $bucket = [
            'taxable' => ['A' => 0.0, 'B' => 0.0, 'C' => 0.0, 'D' => 0.0, 'E' => 0.0],
            'tax' => ['A' => 0.0, 'B' => 0.0, 'C' => 0.0, 'D' => 0.0, 'E' => 0.0],
            'total' => ['A' => 0.0, 'B' => 0.0, 'C' => 0.0, 'D' => 0.0, 'E' => 0.0],
            'taxblAmtA' => 0.0,
            'taxblAmtB' => 0.0,
            'taxblAmtC' => 0.0,
            'taxblAmtD' => 0.0,
            'taxblAmtE' => 0.0,
            'taxAmtA' => 0.0,
            'taxAmtB' => 0.0,
            'taxAmtC' => 0.0,
            'taxAmtD' => 0.0,
            'taxAmtE' => 0.0,
        ];

        foreach ($items as $item) {
            $taxCode = strtoupper(substr($item->taxTyCd, 0, 1));
            $taxCode = in_array($taxCode, ['A', 'B', 'C', 'D', 'E'], true) ? $taxCode : 'A';

            $bucket['taxable'][$taxCode] += $item->taxblAmt;
            $bucket['tax'][$taxCode] += $item->taxAmt;
            $bucket['total'][$taxCode] += $item->totAmt;
        }

        $bucket['taxblAmtA'] = $bucket['taxable']['A'];
        $bucket['taxblAmtB'] = $bucket['taxable']['B'];
        $bucket['taxblAmtC'] = $bucket['taxable']['C'];
        $bucket['taxblAmtD'] = $bucket['taxable']['D'];
        $bucket['taxblAmtE'] = $bucket['taxable']['E'];
        $bucket['taxAmtA'] = $bucket['tax']['A'];
        $bucket['taxAmtB'] = $bucket['tax']['B'];
        $bucket['taxAmtC'] = $bucket['tax']['C'];
        $bucket['taxAmtD'] = $bucket['tax']['D'];
        $bucket['taxAmtE'] = $bucket['tax']['E'];

        return $bucket;
    }
}
