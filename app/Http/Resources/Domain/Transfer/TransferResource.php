<?php

namespace App\Http\Resources\Domain\Transfer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status_transfer' => $this->status,
            'bank_code' => $this->bankCode,
            'scheduled_at' => $this->scheduledAt,
        ];
    }
}
