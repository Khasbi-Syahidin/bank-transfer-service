<?php

namespace App\Domain\Transfer\Enums;

enum TransferStatus: string
{
    case SUCCESS = 'SUCCESS';
    case FAILED = 'FAILED';
    case PENDING = 'PENDING';
}
