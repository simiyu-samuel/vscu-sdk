<?php

declare(strict_types=1);

namespace SimiyuSamuel\VscuSdk\DTOs;

use SimiyuSamuel\VscuSdk\Exceptions\VscuValidationException;

final class ImportItemUpdateDTO
{
    public function __construct(
        public readonly string $taskCd,
        public readonly string $dclDe,
        public readonly int $itemSeq,
        public readonly string $hsCd,
        public readonly string $itemCd,
        public readonly string $itemClsCd,
        public readonly string $imptItemSttsCd,
        public readonly string $tin,
        public readonly string $bhfId,
        public readonly ?string $modrNm = null,
        public readonly ?string $modrId = null,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function make(array $data): self
    {
        self::validate($data);

        return new self(
            taskCd: (string) ($data['taskCd'] ?? ''),
            dclDe: (string) ($data['dclDe'] ?? ''),
            itemSeq: (int) ($data['itemSeq'] ?? 0),
            hsCd: (string) ($data['hsCd'] ?? ''),
            itemCd: (string) ($data['itemCd'] ?? ''),
            itemClsCd: (string) ($data['itemClsCd'] ?? ''),
            imptItemSttsCd: (string) ($data['imptItemSttsCd'] ?? ''),
            tin: (string) ($data['tin'] ?? $data['tpin'] ?? ''),
            bhfId: (string) ($data['bhfId'] ?? ''),
            modrNm: $data['modrNm'] ?? null,
            modrId: $data['modrId'] ?? null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toPayload(): array
    {
        return array_filter([
            'taskCd' => $this->taskCd,
            'dclDe' => $this->dclDe,
            'itemSeq' => $this->itemSeq,
            'hsCd' => $this->hsCd,
            'itemCd' => $this->itemCd,
            'itemClsCd' => $this->itemClsCd,
            'imptItemSttsCd' => $this->imptItemSttsCd,
            'tin' => $this->tin,
            'bhfId' => $this->bhfId,
            'modrNm' => $this->modrNm,
            'modrId' => $this->modrId,
        ], static fn ($value) => $value !== null);
    }

    /**
     * @param array<string, mixed> $data
     */
    private static function validate(array $data): void
    {
        $missing = [];

        foreach (['taskCd', 'dclDe', 'itemSeq', 'hsCd', 'itemCd', 'itemClsCd', 'imptItemSttsCd'] as $field) {
            if (!array_key_exists($field, $data) || $data[$field] === '' || $data[$field] === null) {
                $missing[] = $field;
            }
        }

        if (!empty($missing)) {
            throw new VscuValidationException('Missing required ImportItemUpdateDTO fields: ' . implode(', ', $missing));
        }
    }
}
