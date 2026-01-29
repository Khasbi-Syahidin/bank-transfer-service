<?php

namespace App\Infrastructure\Bank\Alpha;

use App\Domain\Transfer\DTO\TransferRequest;
use App\Domain\Transfer\Services\BankClientInterface;
use App\Domain\Transfer\Services\BankTransferResponse;

final class AlphaBankClient implements BankClientInterface
{
    public function __construct(
        private AlphaBankApi $alphaApi
    ) {}

    public function getBankCode(): string
    {
        return 'A01';
    }

    public function transfer(TransferRequest $request): BankTransferResponse
    {
        $balance = $this->alphaApi->checkBalance($request->currency->value);

        if ($balance < $request->amount) {
            return new BankTransferResponse(
                success: false,
                message: 'Insufficient balance (Alpha)'
            );
        }

        $success = $this->alphaApi->send(
            $request->source_account,
            $this->getBankCode(),
            $request->amount,
            $request->currency->value
        );

        return new BankTransferResponse(
            success: $success,
            message: $success
                ? 'Transfer success via Bank Alpha'
                : 'Transfer failed via Bank Alpha'
        );
    }
}
