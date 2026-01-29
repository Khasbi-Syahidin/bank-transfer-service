<?php

namespace App\Infrastructure\Bank\Charlie;

interface CharlieBankInterface
{
    public function getBalance(): array;

    public function transferIDR(
        string $account,
        int $transferType,
        string $bankCode,
        float $amount,
    ): bool;

    public function transferUSD(
        string $account,
        int $transferType,
        string $bankCode,
        float $amount,
    ): bool;
}
