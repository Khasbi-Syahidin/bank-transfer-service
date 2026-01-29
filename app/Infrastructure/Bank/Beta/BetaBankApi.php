<?php

namespace App\Infrastructure\Bank\Beta;

class BetaBankApi implements BetaBankInterface
{
    public function transferInHouse(
        string $account,
        float $amount,
        string $currency
    ): float {
        return 8_000_000_000_000 - $amount;
    }

    public function transferOnline(
        string $account,
        string $bankCode,
        float $amount,
        string $currency
    ): bool {
        return $amount <= 3_000_000;
    }
}
