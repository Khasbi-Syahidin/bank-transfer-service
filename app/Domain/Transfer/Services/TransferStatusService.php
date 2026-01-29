<?php

namespace App\Domain\Transfer\Services;

use App\Infrastructure\TransferLog\TransferLogRepositoryInterface;
use App\Models\TransferLog;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TransferStatusService implements TransferStatusServiceInterface
{
    public function __construct(
        private TransferLogRepositoryInterface $repository
    ) {}

    public function check(string $transferId): TransferLog
    {
        $log = $this->repository->findByTransferId($transferId);

        if (! $log) {
            throw new ModelNotFoundException('Transfer log not found');
        }

        return $log;
    }
}
