<?php

declare(strict_types=1);

namespace SimiyuSamuel\VscuSdk\DTOs;

final class DebitNoteDTO
{
    /**
     * @param InvoiceLineDTO[] $itemList
     */
    public function __construct(
        public readonly string $debitNo,
        public readonly string $orgInvcNo,
        public readonly string $tpin,
        public readonly string $custTpin,
        public readonly ?string $custNm = null,
        public readonly string $cfmDt = '',
        public readonly string $salesDt = '',
        public readonly string $pmtTyCd = '01',
        public readonly float $totAmt = 0.0,
        public readonly float $totTaxblAmt = 0.0,
        public readonly float $totTaxAmt = 0.0,
        public readonly string $curCd = 'KES',
        public readonly ?array $receipt = null,
        public readonly array $itemList = [],
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function make(array $data): self
    {
        $items = $data['itemList'] ?? $data['items'] ?? [];
        $items = array_map(
            fn (mixed $item) => $item instanceof InvoiceLineDTO ? $item : InvoiceLineDTO::make((array) $item),
            is_array($items) ? $items : []
        );

        return new self(
            debitNo: (string) ($data['debitNo'] ?? $data['invcNo'] ?? $data['debit_note_number'] ?? ''),
            orgInvcNo: (string) ($data['orgInvcNo'] ?? $data['original_invoice_number'] ?? ''),
            tpin: (string) ($data['tpin'] ?? $data['supplier_pin'] ?? ''),
            custTpin: (string) ($data['custTpin'] ?? $data['custTin'] ?? $data['buyer_pin'] ?? ''),
            custNm: $data['custNm'] ?? $data['buyer_name'] ?? null,
            cfmDt: (string) ($data['cfmDt'] ?? $data['debit_date'] ?? $data['invoice_date'] ?? ''),
            salesDt: (string) ($data['salesDt'] ?? $data['debit_date'] ?? $data['invoice_date'] ?? ''),
            pmtTyCd: (string) ($data['pmtTyCd'] ?? $data['payment_type'] ?? '01'),
            totAmt: (float) ($data['totAmt'] ?? $data['debit_amount'] ?? 0),
            totTaxblAmt: (float) ($data['totTaxblAmt'] ?? $data['taxable_amount'] ?? 0),
            totTaxAmt: (float) ($data['totTaxAmt'] ?? $data['vat_amount'] ?? 0),
            curCd: (string) ($data['curCd'] ?? $data['currency'] ?? 'KES'),
            receipt: isset($data['receipt']) && is_array($data['receipt']) ? $data['receipt'] : null,
            itemList: $items,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toInvoicePayload(): array
    {
        return array_filter([
            'invcNo' => $this->debitNo,
            'orgInvcNo' => $this->orgInvcNo,
            'tpin' => $this->tpin,
            'custTpin' => $this->custTpin,
            'custNm' => $this->custNm,
            'salesTyCd' => 'N',
            'rcptTyCd' => 'D',
            'pmtTyCd' => $this->pmtTyCd,
            'salesSttsCd' => '02',
            'cfmDt' => $this->cfmDt,
            'salesDt' => $this->salesDt,
            'totItemCnt' => count($this->itemList),
            'totTaxblAmt' => $this->totTaxblAmt,
            'totTaxAmt' => $this->totTaxAmt,
            'totAmt' => $this->totAmt,
            'curCd' => $this->curCd,
            'receipt' => $this->receipt ?? [
                'custTin' => $this->custTpin,
                'custNm' => $this->custNm,
                'prchrAcptcYn' => 'N',
                'topMsg' => 'Debit Note',
                'btmMsg' => 'Thank You!',
            ],
            'itemList' => array_map(fn (InvoiceLineDTO $item) => $item->toPayload(), $this->itemList),
        ], static fn ($value) => $value !== null);
    }
}
