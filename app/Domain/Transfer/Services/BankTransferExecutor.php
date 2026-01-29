<?php

namespace App\Domain\Transfer\Services;

use App\Domain\Transfer\DTO\TransferRequest;
use App\Domain\Transfer\DTO\TransferResult;
use App\Domain\Transfer\DTO\TransferScheduleResult;
use App\Domain\Transfer\Enums\TransferStatus;
use App\Infrastructure\TransferLog\TransferLogRepositoryInterface;

class BankTransferExecutor implements BankTransferExecutorInterface
{
    public function __construct(
        private BankClientRegistry $bankRegistry,
        private TransferLogRepositoryInterface $logRepository
    ) {}

    public function executeTransferWithFallback(
        TransferRequest $request,
        TransferScheduleResult $schedule
    ): TransferResult {
        $lastFailedResult = null;
        foreach ($schedule->priorityBanks as $bankCode) {
            try {
                $bankClient = $this->bankRegistry->getClient($bankCode);
                $bankResponse = $bankClient->transfer($request);

                $result = new TransferResult(
                    success: $bankResponse->success,
                    status: $bankResponse->success ? TransferStatus::SUCCESS : TransferStatus::FAILED,
                    bankCode: $bankClient->getBankCode(),
                    message: $bankResponse->message
                );

                if ($result->success) {
                    $this->logRepository->save($request, $result);

                    return $result;
                } else {
                    $lastFailedResult = $result;
                }
            } catch (\Exception $e) {
                $lastFailedResult = new TransferResult(
                    success: false,
                    status: TransferStatus::FAILED,
                    bankCode: $bankCode,
                    message: 'Exception during transfer attempt: '.$e->getMessage()
                );
            }
        }

        if ($lastFailedResult) {
            $this->logRepository->save($request, $lastFailedResult);

            return $lastFailedResult;
        }

        $result = new TransferResult(
            success: false,
            status: TransferStatus::FAILED,
            bankCode: null,
            message: 'No available bank client or all priority banks failed.'
        );
        $this->logRepository->save($request, $result);

        return $result;
    }
}
