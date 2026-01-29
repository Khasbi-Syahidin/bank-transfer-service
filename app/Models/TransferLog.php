<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferLog extends Model
{
    protected $fillable = [
        'transfer_id',
        'source_account',
        'destination_account',
        'amount',
        'currency',
        'bank_code',
        'status',
        'message',
        'scheduled_at',
        'requested_at',
    ];
}
