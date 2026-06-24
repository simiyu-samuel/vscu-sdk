<?php

declare(strict_types=1);

namespace SimiyuSamuel\VscuSdk\DTOs;

use SimiyuSamuel\VscuSdk\Exceptions\VscuValidationException;

final class DeviceInitDTO
{
    public function __construct(
        public readonly string $tpin,
        public readonly string $bhfId,
        public readonly string $dvcSrlNo,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function make(array $data): self
    {
        self::validate($data);

        return new self(
            tpin: (string) ($data['tpin'] ?? $data['tin'] ?? ''),
            bhfId: (string) ($data['bhfId'] ?? $data['branch_id'] ?? '00'),
            dvcSrlNo: (string) ($data['dvcSrlNo'] ?? $data['device_serial_no'] ?? ''),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toPayload(): array
    {
        return [
            'tpin' => $this->tpin,
            'bhfId' => $this->bhfId,
            'dvcSrlNo' => $this->dvcSrlNo,
        ];
    }

    /**
     * @param array<string, mixed> $data
     */
    private static function validate(array $data): void
    {
        $missing = [];

        if (empty($data['tpin']) && empty($data['tin'])) {
            $missing[] = 'tpin';
        }

        if (empty($data['dvcSrlNo']) && empty($data['device_serial_no'])) {
            $missing[] = 'dvcSrlNo';
        }

        if (!empty($missing)) {
            throw new VscuValidationException('Missing required DeviceInitDTO fields: ' . implode(', ', $missing));
        }
    }
}
