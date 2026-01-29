<?php

namespace App\Domain\Transfer\DTO;

use DateTimeImmutable;

class TransferScheduleResult
{
    public function __construct(
        public readonly bool $allowed,
        public readonly bool $pending,
        public readonly ?DateTimeImmutable $scheduledAt,
        public readonly array $priorityBanks
    ) {}
}
