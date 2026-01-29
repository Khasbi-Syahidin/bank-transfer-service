<?php

namespace App\Domain\Transfer\DTO;

use App\Domain\Transfer\Enums\TransferStatus;

final class TransferResult
{
    public function __construct(
        public readonly bool $success,
        public readonly TransferStatus $status,
        public readonly ?string $bankCode,
        public readonly string $message,
        public readonly ?string $scheduledAt = null,
    ) {}
}
