<?php

namespace App\Infrastructure\Bank\Alpha;

interface AlphaBankInterface
{
    public function checkBalance(string $currency): float;

    public function send(
        string $account,
        string $bankCode,
        float $amount,
        string $currency
    ): bool;
}
