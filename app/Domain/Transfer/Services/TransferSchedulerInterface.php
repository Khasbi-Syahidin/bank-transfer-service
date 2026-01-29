<?php

namespace App\Domain\Transfer\Services;

use App\Domain\Transfer\DTO\TransferScheduleResult;
use App\Domain\Transfer\Enums\Currency;
use DateTimeInterface;

interface TransferSchedulerInterface
{
    public function resolve(
        DateTimeInterface $transfer_time,
        Currency $currency,
    ): TransferScheduleResult;
}
