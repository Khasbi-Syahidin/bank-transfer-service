<?php

namespace App\Domain\Transfer\Services;

use App\Domain\Transfer\DTO\TransferRequest;

interface BankClientInterface
{
    public function getBankCode(): string;

    public function transfer(
        TransferRequest $request
    ): BankTransferResponse;
}
