<?php

declare(strict_types=1);

namespace SimiyuSamuel\VscuSdk\DTOs;

use SimiyuSamuel\VscuSdk\Exceptions\VscuValidationException;

final class StockMasterDTO
{
    public function __construct(
        public readonly string $itemCd,
        public readonly float $rsdQty,
        public readonly string $tin,
        public readonly string $bhfId,
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
            itemCd: (string) ($data['itemCd'] ?? $data['item_code'] ?? ''),
            rsdQty: (float) ($data['rsdQty'] ?? $data['remaining_qty'] ?? 0),
            tin: (string) ($data['tin'] ?? $data['tpin'] ?? ''),
            bhfId: (string) ($data['bhfId'] ?? ''),
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
            'itemCd' => $this->itemCd,
            'rsdQty' => $this->rsdQty,
            'tin' => $this->tin,
            'bhfId' => $this->bhfId,
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

        foreach (['itemCd', 'rsdQty', 'tin', 'bhfId'] as $field) {
            if (!array_key_exists($field, $data) || $data[$field] === '' || $data[$field] === null) {
                $missing[] = $field;
            }
        }

        if (!empty($missing)) {
            throw new VscuValidationException('Missing required StockMasterDTO fields: ' . implode(', ', $missing));
        }
    }
}
