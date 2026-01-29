<?php

namespace App\Domain\Transfer\DTO;

use App\Domain\Transfer\Enums\Currency;
use App\Domain\Transfer\ValueObjects\BankCode;
use DateTimeImmutable;

class TransferRequest
{
    public function __construct(
        public readonly string $transfer_id,
        public readonly BankCode $source_bank_code,
        public readonly string $source_account,
        public readonly BankCode $destination_bank_code,
        public readonly string $destination_account,
        public readonly float $amount,
        public readonly Currency $currency,
        public readonly string $description,
        public readonly DateTimeImmutable $transfer_time
    ) {}
}
