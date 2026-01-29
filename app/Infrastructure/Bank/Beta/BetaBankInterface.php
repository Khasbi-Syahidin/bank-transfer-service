<?php

namespace App\Infrastructure\Bank\Beta;

interface BetaBankInterface
{
    public function transferInHouse(
        string $account,
        float $amount,
        string $currency
    ): float;

    public function transferOnline(
        string $account,
        string $bankCode,
        float $amount,
        string $currency
    ): bool;
}
