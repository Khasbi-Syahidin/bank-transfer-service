<?php

namespace App\Http\Resources\Domain\Transfer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransferScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            'allowed' => $this->allowed,
            'pending' => $this->pending,
            'scheduled_at' => $this->scheduledAt?->format('Y-m-d H:i:s'),
            'priority_banks' => $this->priorityBanks,
        ];
    }
}
