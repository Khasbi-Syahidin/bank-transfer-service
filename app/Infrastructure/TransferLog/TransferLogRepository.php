<?php

namespace App\Infrastructure\TransferLog;

use App\Domain\Transfer\DTO\TransferRequest;
use App\Domain\Transfer\DTO\TransferResult;
use App\Models\TransferLog;

class TransferLogRepository implements TransferLogRepositoryInterface
{
    public function save(
        TransferRequest $request,
        TransferResult $result
    ): void {
        TransferLog::create([
            'transfer_id' => $request->transfer_id,
            'source_account' => $request->source_account,
            'destination_account' => $request->destination_account,
            'amount' => $request->amount,
            'currency' => $request->currency->value,
            'bank_code' => $result->bankCode,
            'status' => $result->status,
            'message' => $result->message,
            'scheduled_at' => $result->scheduledAt,
            'requested_at' => $request->transfer_time,
        ]);
    }

    public function findByTransferId(string $transferId): ?TransferLog
    {
        return TransferLog::where('transfer_id', $transferId)->first();
    }
}
