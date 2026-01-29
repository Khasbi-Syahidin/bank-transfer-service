<?php

namespace App\Domain\Transfer\Services;

use App\Domain\Transfer\DTO\TransferRequest;
use App\Domain\Transfer\DTO\TransferResult;
use App\Domain\Transfer\Enums\TransferStatus;
use App\Infrastructure\TransferLog\TransferLogRepositoryInterface;

class BankService implements BankServiceInterface
{
    public function __construct(
        private TransferSchedulerInterface $scheduler,
        private BankTransferExecutorInterface $bankTransferExecutor,
        private TransferLogRepositoryInterface $logRepository
    ) {}

    public function sendMoney(TransferRequest $request): TransferResult
    {
        $schedule = $this->scheduler->resolve(
            $request->transfer_time,
            $request->currency
        );

        if ($schedule->pending) {
            $result = new TransferResult(
                success: true,
                status: TransferStatus::PENDING,
                bankCode: null,
                message: 'Transfer is scheduled',
                scheduledAt: $schedule->scheduledAt?->format('H:i')
            );
            $this->logRepository->save($request, $result);

            return $result;
        }

        return $this->bankTransferExecutor->executeTransferWithFallback($request, $schedule);
    }
}
