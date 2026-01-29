<?php

namespace App\Infrastructure\Bank\Charlie;

class CharlieBankApi implements CharlieBankInterface
{
    public function getBalance(): array
    {
        return [
            'IDR' => 15_000_000_000_000,
            'USD' => 7_500_000_000_000,
        ];
    }

    public function transferIDR(
        string $account,
        int $transferType,
        string $bankCode,
        float $amount,
    ): bool {
        return $amount <= 5_000_000;
    }

    public function transferUSD(
        string $account,
        int $transferType,
        string $bankCode,
        float $amount,
    ): bool {
        return $amount <= 2_000;
    }
}
