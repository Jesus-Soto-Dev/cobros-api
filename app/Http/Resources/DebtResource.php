<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DebtResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $paidAmount = (float) $this->payments()->sum('amount');
        $amount = (float) $this->amount;

        return [
            'id' => $this->id,
            'debtor' => [
                'id' => $this->debtor->id,
                'name' => $this->debtor->name,
            ],
            'amount' => number_format($amount, 2, '.', ''),
            'paid_amount' => number_format($paidAmount, 2, '.', ''),
            'remaining_amount' => number_format(max(0, $amount - $paidAmount), 2, '.', ''),
            'due_date' => $this->due_date->format('Y-m-d'),
            'status' => $this->status->value,
            'status_label' => $this->status->label(),
            'notes' => $this->notes,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),

            'actions' => DebtActionResource::collection($this->whenLoaded('actions')),
            'payments' => PaymentResource::collection($this->whenLoaded('payments')),
        ];
    }
}
