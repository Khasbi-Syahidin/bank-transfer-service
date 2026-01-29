<?php

namespace App\Domain\Transfer\Services;

final class BankTransferResponse
{
    public function __construct(
        public readonly bool $success,
        public readonly string $message
    ) {}
}
