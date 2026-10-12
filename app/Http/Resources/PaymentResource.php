<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => number_format((float) $this->amount, 2, '.', ''),
            'payment_date' => $this->payment_date->format('Y-m-d'),
            'method' => $this->method?->value,
            'method_label' => $this->method?->label(),
            'notes' => $this->notes,
            'created_at' => $this->created_at->toIso8601String(),

            // Deuda actualizada (cuando está cargada)
            'debt' => $this->whenLoaded('debt', fn () => [
                'id' => $this->debt->id,
                'amount' => number_format((float) $this->debt->amount, 2, '.', ''),
                'paid_amount' => number_format((float) $this->debt->payments()->sum('amount'), 2, '.', ''),
                'remaining_amount' => number_format(
                    max(0, (float) $this->debt->amount - (float) $this->debt->payments()->sum('amount')),
                    2,
                    '.',
                    ''
                ),
                'status' => $this->debt->status->value,
                'status_label' => $this->debt->status->label(),
            ]),
        ];
    }
}
