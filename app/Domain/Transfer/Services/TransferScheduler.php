<?php

namespace App\Domain\Transfer\Services;

use App\Domain\Transfer\DTO\TransferScheduleResult;
use App\Domain\Transfer\Enums\Currency;
use DateTimeImmutable;
use DateTimeInterface;

class TransferScheduler implements TransferSchedulerInterface
{
    public function resolve(
        DateTimeInterface $transfer_time,
        Currency $currency
    ): TransferScheduleResult {

        $hour = (int) $transfer_time->format('H');

        return match (true) {
            // 00:00 - 03:59 - No interbank transfer
            $hour >= 0 && $hour < 4 => new TransferScheduleResult(
                allowed: false,
                pending: true,
                scheduledAt: (new DateTimeImmutable)->setTime(4, 0),
                priorityBanks: []
            ),

            // 04:00 - 09:59 Alpha → Beta → Charlie all currency
            $hour >= 4 && $hour < 10 => new TransferScheduleResult(
                allowed: true,
                pending: false,
                scheduledAt: null,
                priorityBanks: ['A01', 'B02', 'C03']
            ),

            // 10:00 - 16:59 Beta → Charlie → Alpha all currency
            $hour >= 10 && $hour < 17 => new TransferScheduleResult(
                allowed: true,
                pending: false,
                scheduledAt: null,
                priorityBanks: ['B02', 'C03', 'A01']
            ),

            // 17:00 - 21:59 Charlie → Alpha → Beta IDR
            $hour >= 17 && $hour < 22 => (function () use ($currency) {
                if ($currency === Currency::IDR) {
                    return new TransferScheduleResult(
                        allowed: true,
                        pending: false,
                        scheduledAt: null,
                        priorityBanks: ['C03', 'A01', 'B02']
                    );
                } else {
                    // Transfer USD pada jam 17:00-21:59 tunda hingga jam 22:00
                    return new TransferScheduleResult(
                        allowed: false,
                        pending: true,
                        scheduledAt: (new DateTimeImmutable)->setTime(22, 0),
                        priorityBanks: []
                    );
                }
            })(),

            // 22:00 - 23:59 Beta only
            $hour >= 22 && $hour <= 23 => new TransferScheduleResult(
                allowed: true,
                pending: false,
                scheduledAt: null,
                priorityBanks: ['B02']
            ),

            // Default case
            default => new TransferScheduleResult(
                allowed: false,
                pending: false,
                scheduledAt: null,
                priorityBanks: []
            ),
        };
    }
}
