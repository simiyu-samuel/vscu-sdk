<?php

declare(strict_types=1);

namespace SimiyuSamuel\VscuSdk\DTOs;

final class VscuResponseDTO
{
    public function __construct(
        public readonly bool $successful,
        public readonly string $resultCd,
        public readonly string $resultMsg,
        public readonly ?string $rcptNo = null,
        public readonly ?string $intrlData = null,
        public readonly ?string $rcptSign = null,
        public readonly array $data = [],
        public readonly array $raw = [],
    ) {}

    /**
     * @param array<string, mixed> $response
     */
    public static function fromArray(array $response): self
    {
        $resultCd = (string) ($response['resultCd'] ?? $response['result_cd'] ?? '');
        $data = is_array($response['data'] ?? null) ? $response['data'] : [];

        return new self(
            successful: in_array($resultCd, ['000', '0000', '200'], true),
            resultCd: $resultCd,
            resultMsg: (string) ($response['resultMsg'] ?? $response['result_msg'] ?? ''),
            rcptNo: $data['rcptNo'] ?? null,
            intrlData: $data['intrlData'] ?? null,
            rcptSign: $data['rcptSign'] ?? null,
            data: $data,
            raw: $response,
        );
    }

    public function isSuccessful(): bool
    {
        return $this->successful;
    }
}
