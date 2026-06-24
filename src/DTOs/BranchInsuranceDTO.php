<?php

declare(strict_types=1);

namespace SimiyuSamuel\VscuSdk\DTOs;

use SimiyuSamuel\VscuSdk\Exceptions\VscuValidationException;

final class BranchInsuranceDTO
{
    public function __construct(
        public readonly string $bhfId,
        public readonly string $isrccCd,
        public readonly string $isrccNm,
        public readonly float $isrcRt,
        public readonly string $useYn,
        public readonly string $tin,
        public readonly ?string $regrNm = null,
        public readonly ?string $regrId = null,
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
            bhfId: (string) ($data['bhfId'] ?? ''),
            isrccCd: (string) ($data['isrccCd'] ?? ''),
            isrccNm: (string) ($data['isrccNm'] ?? ''),
            isrcRt: (float) ($data['isrcRt'] ?? 0),
            useYn: (string) ($data['useYn'] ?? 'Y'),
            tin: (string) ($data['tin'] ?? $data['tpin'] ?? ''),
            regrNm: $data['regrNm'] ?? null,
            regrId: $data['regrId'] ?? null,
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
            'bhfId' => $this->bhfId,
            'isrccCd' => $this->isrccCd,
            'isrccNm' => $this->isrccNm,
            'isrcRt' => $this->isrcRt,
            'useYn' => $this->useYn,
            'tin' => $this->tin,
            'regrNm' => $this->regrNm,
            'regrId' => $this->regrId,
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

        foreach (['bhfId', 'isrccCd', 'isrccNm', 'isrcRt', 'useYn'] as $field) {
            if (!array_key_exists($field, $data) || $data[$field] === '' || $data[$field] === null) {
                $missing[] = $field;
            }
        }

        if (!empty($missing)) {
            throw new VscuValidationException('Missing required BranchInsuranceDTO fields: ' . implode(', ', $missing));
        }
    }
}
