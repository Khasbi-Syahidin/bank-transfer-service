<?php

namespace App\Domain\Transfer\Services;

use App\Models\TransferLog;

interface TransferStatusServiceInterface
{
    public function check(string $transferId): TransferLog;
}
