<?php

namespace App\Infrastructure\Bank\Beta;

use App\Domain\Transfer\DTO\TransferRequest;
use App\Domain\Transfer\Services\BankClientInterface;
use App\Domain\Transfer\Services\BankTransferResponse;

final class BetaBankClient implements BankClientInterface
{
    public function __construct(
        private BetaBankApi $betaApi
    ) {}

    public function getBankCode(): string
    {
        return 'B02';
    }

    public function transfer(TransferRequest $request): BankTransferResponse
    {
        $balance = $this->betaApi->transferInHouse(
            $request->source_account,
            $request->amount,
            $request->currency->value,
        );

        if ($balance < $request->amount) {
            return new BankTransferResponse(
                success: false,
                message: 'Insufficient balance (Beta)'
            );
        }

        $success = $this->betaApi->transferOnline(
            $request->source_account,
            $this->getBankCode(),
            $request->amount,
            $request->currency->value,
        );

        return new BankTransferResponse(
            success: $success,
            message: $success
                ? 'Transfer success via Bank Beta'
                : 'Transfer failed via Bank Beta'
        );
    }
}
