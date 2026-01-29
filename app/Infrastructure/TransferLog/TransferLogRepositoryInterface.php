<?php

namespace App\Infrastructure\TransferLog;

use App\Domain\Transfer\DTO\TransferRequest;
use App\Domain\Transfer\DTO\TransferResult;
use App\Models\TransferLog;

interface TransferLogRepositoryInterface
{
    public function save(
        TransferRequest $request,
        TransferResult $result
    ): void;

    public function findByTransferId(string $transferId): ?TransferLog;
}
