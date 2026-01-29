<?php

namespace App\Domain\Transfer\ValueObjects;

class BankCode
{
    public function __construct(
        private string $code
    ) {}

    public function value(): string
    {
        return $this->code;
    }
}
