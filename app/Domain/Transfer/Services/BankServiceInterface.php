<?php

namespace App\Domain\Transfer\Services;

use App\Domain\Transfer\DTO\TransferRequest;
use App\Domain\Transfer\DTO\TransferResult;

interface BankServiceInterface
{
    public function sendMoney(TransferRequest $request): TransferResult;
}
