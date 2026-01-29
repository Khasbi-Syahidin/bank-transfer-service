<?php

namespace App\Infrastructure\Bank\Charlie;

use App\Domain\Transfer\DTO\TransferRequest;
use App\Domain\Transfer\Enums\Currency;
use App\Domain\Transfer\Services\BankClientInterface;
use App\Domain\Transfer\Services\BankTransferResponse;

final class CharlieBankClient implements BankClientInterface
{
    private const TRANSFER_TYPE_ONLINE = 1;

    public function __construct(
        private CharlieBankApi $charlieApi
    ) {}

    public function getBankCode(): string
    {
        return 'C03';
    }

    public function transfer(TransferRequest $request): BankTransferResponse
    {
        $balances = $this->charlieApi->getBalance();

        $currencyKey = $request->currency->value;

        if (
            isset($balances[$currencyKey]) &&
            $balances[$currencyKey] < $request->amount
        ) {
            return new BankTransferResponse(
                success: false,
                message: 'Insufficient balance (Charlie)'
            );
        }

        $success = match ($request->currency) {
            Currency::IDR => $this->charlieApi->transferIDR(
                $request->source_account,
                self::TRANSFER_TYPE_ONLINE,
                $this->getBankCode(),
                $request->amount
            ),
            Currency::USD => $this->charlieApi->transferUSD(
                $request->source_account,
                self::TRANSFER_TYPE_ONLINE,
                $this->getBankCode(),
                $request->amount
            ),
        };

        return new BankTransferResponse(
            success: $success,
            message: $success
                ? 'Transfer success via Bank Charlie'
                : 'Transfer failed via Bank Charlie'
        );
    }
}
