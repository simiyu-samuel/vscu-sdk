<?php

declare(strict_types=1);

namespace SimiyuSamuel\VscuSdk\DTOs;

use SimiyuSamuel\VscuSdk\Exceptions\VscuValidationException;

final class InvoiceDTO
{
    /**
     * @param InvoiceLineDTO[] $itemList
     * @param array<string, mixed>|null $receipt
     */
    public function __construct(
        public readonly string $invcNo,
        public readonly string $tpin,
        public readonly string $custTpin,
        public readonly ?string $custNm,
        public readonly string $rcptTyCd,
        public readonly string $pmtTyCd,
        public readonly string $salesTyCd = 'N',
        public readonly string $salesSttsCd = '02',
        public readonly string $cfmDt = '',
        public readonly string $salesDt = '',
        public readonly ?string $stockRlsDt = null,
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
        public readonly string $prchrAcptcYn = 'N',
        public readonly string $curCd = 'KES',
        public readonly ?string $orgInvcNo = null,
        public readonly ?string $rfdRsnCd = null,
        public readonly ?array $receipt = null,
        public readonly array $itemList = [],
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

        $items = $data['itemList'] ?? $data['items'] ?? [];
        $items = array_map(
            fn (mixed $item) => $item instanceof InvoiceLineDTO ? $item : InvoiceLineDTO::make((array) $item),
            is_array($items) ? $items : []
        );

        return new self(
            invcNo: (string) ($data['invcNo'] ?? $data['invoice_number'] ?? ''),
            tpin: (string) ($data['tpin'] ?? $data['supplier_pin'] ?? ''),
            custTpin: (string) ($data['custTpin'] ?? $data['custTin'] ?? $data['buyer_pin'] ?? ''),
            custNm: $data['custNm'] ?? $data['buyer_name'] ?? null,
            rcptTyCd: (string) ($data['rcptTyCd'] ?? $data['invoice_type'] ?? 'S'),
            pmtTyCd: (string) ($data['pmtTyCd'] ?? $data['payment_type'] ?? '01'),
            salesTyCd: (string) ($data['salesTyCd'] ?? 'N'),
            salesSttsCd: (string) ($data['salesSttsCd'] ?? '02'),
            cfmDt: (string) ($data['cfmDt'] ?? $data['invoice_date'] ?? $data['salesDt'] ?? ''),
            salesDt: (string) ($data['salesDt'] ?? $data['invoice_date'] ?? ''),
            stockRlsDt: $data['stockRlsDt'] ?? null,
            totItemCnt: (int) ($data['totItemCnt'] ?? count($items)),
            taxblAmtA: (float) ($data['taxblAmtA'] ?? 0),
            taxblAmtB: (float) ($data['taxblAmtB'] ?? 0),
            taxblAmtC: (float) ($data['taxblAmtC'] ?? 0),
            taxblAmtD: (float) ($data['taxblAmtD'] ?? 0),
            taxblAmtE: (float) ($data['taxblAmtE'] ?? 0),
            taxRtA: (float) ($data['taxRtA'] ?? 0),
            taxRtB: (float) ($data['taxRtB'] ?? 0),
            taxRtC: (float) ($data['taxRtC'] ?? 0),
            taxRtD: (float) ($data['taxRtD'] ?? 0),
            taxRtE: (float) ($data['taxRtE'] ?? 0),
            taxAmtA: (float) ($data['taxAmtA'] ?? 0),
            taxAmtB: (float) ($data['taxAmtB'] ?? 0),
            taxAmtC: (float) ($data['taxAmtC'] ?? 0),
            taxAmtD: (float) ($data['taxAmtD'] ?? 0),
            taxAmtE: (float) ($data['taxAmtE'] ?? 0),
            totTaxblAmt: (float) ($data['totTaxblAmt'] ?? $data['taxblAmt'] ?? 0),
            totTaxAmt: (float) ($data['totTaxAmt'] ?? $data['taxAmt'] ?? $data['vatAmt'] ?? 0),
            totAmt: (float) ($data['totAmt'] ?? $data['total_amount'] ?? 0),
            prchrAcptcYn: (string) ($data['prchrAcptcYn'] ?? 'N'),
            curCd: (string) ($data['curCd'] ?? $data['currency'] ?? 'KES'),
            orgInvcNo: $data['orgInvcNo'] ?? null,
            rfdRsnCd: $data['rfdRsnCd'] ?? null,
            receipt: isset($data['receipt']) && is_array($data['receipt']) ? $data['receipt'] : null,
            itemList: $items,
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
        $payload = [
            'invcNo' => $this->invcNo,
            'tpin' => $this->tpin,
            'custTpin' => $this->custTpin,
            'custNm' => $this->custNm,
            'salesTyCd' => $this->salesTyCd,
            'rcptTyCd' => $this->rcptTyCd,
            'pmtTyCd' => $this->pmtTyCd,
            'salesSttsCd' => $this->salesSttsCd,
            'cfmDt' => $this->cfmDt,
            'salesDt' => $this->salesDt,
            'stockRlsDt' => $this->stockRlsDt ?? $this->cfmDt,
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
            'prchrAcptcYn' => $this->prchrAcptcYn,
            'curCd' => $this->curCd,
            'orgInvcNo' => $this->orgInvcNo,
            'rfdRsnCd' => $this->rfdRsnCd,
            'receipt' => $this->receipt ?? [
                'custTin' => $this->custTpin,
                'custNm' => $this->custNm,
                'prchrAcptcYn' => $this->prchrAcptcYn,
                'topMsg' => 'Thank You!',
                'btmMsg' => 'Come Again!',
            ],
            'itemList' => array_map(fn (InvoiceLineDTO $item) => $item->toPayload(), $this->itemList),
            'regrId' => $this->regrId,
            'regrNm' => $this->regrNm,
            'modrId' => $this->modrId,
            'modrNm' => $this->modrNm,
        ];

        return array_filter($payload, static fn ($value) => $value !== null);
    }

    /**
     * @param array<string, mixed> $data
     */
    private static function validate(array $data): void
    {
        $required = [
            ['invcNo', 'invoice_number'],
            ['tpin', 'supplier_pin'],
            ['custTpin', 'custTin', 'buyer_pin'],
            ['rcptTyCd', 'invoice_type'],
            ['pmtTyCd', 'payment_type'],
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
            throw new VscuValidationException('Missing required InvoiceDTO fields: ' . implode(', ', $missing));
        }
    }
}
