<?php

namespace App\Domain\Transfer\Services;

use App\Domain\Transfer\DTO\TransferRequest;
use App\Domain\Transfer\DTO\TransferResult;
use App\Domain\Transfer\DTO\TransferScheduleResult;

interface BankTransferExecutorInterface
{
    public function executeTransferWithFallback(
        TransferRequest $request,
        TransferScheduleResult $schedule
    ): TransferResult;
}