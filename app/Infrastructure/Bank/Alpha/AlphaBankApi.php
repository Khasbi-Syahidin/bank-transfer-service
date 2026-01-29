<?php

namespace App\Infrastructure\Bank\Alpha;

class AlphaBankApi implements AlphaBankInterface
{
    public function checkBalance(string $currency): float
    {
        return match ($currency) {
            'IDR' => 10_000_000_000_000,
            'USD' => 5_000_000_000_000,
            default => 0,
        };
    }

    public function send(
        string $account,
        string $bankCode,
        float $amount,
        string $currency
    ): bool {
        return $amount > 0;
    }
}
