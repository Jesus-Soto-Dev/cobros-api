<?php

namespace App\Services;

use App\Enums\DebtStatus;
use App\Models\Debt;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaymentService
{
    public function registerPayment(Debt $debt, array $data): Payment
    {
        return DB::transaction(function () use ($debt, $data) {
            // 1. Bloquea la deuda para evitar condiciones de carrera.
            $debt = Debt::whereKey($debt->id)->lockForUpdate()->first();

            // 2. Comprueba reglas de negocio.
            if (in_array($debt->status, [DebtStatus::Paid, DebtStatus::Uncollectible], true)) {
                throw ValidationException::withMessages([
                    'amount' => ['No se pueden registrar pagos en una deuda '.$debt->status->label().'.'],
                ]);
            }

            $paidSoFar = (float) $debt->payments()->sum('amount');
            $newTotal = $paidSoFar + (float) $data['amount'];

            if ($newTotal > (float) $debt->amount) {
                throw ValidationException::withMessages([
                    'amount' => ['El pago excede el importe pendiente de la deuda.'],
                ]);
            }

            // 3. Crea el pago.
            $payment = $debt->payments()->create([
                'amount' => $data['amount'],
                'payment_date' => $data['payment_date'] ?? now()->toDateString(),
                'method' => $data['method'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            // 4. Si la deuda queda cubierta, se marca como pagada.
            if ($newTotal >= (float) $debt->amount) {
                $debt->update(['status' => DebtStatus::Paid]);
            }

            return $payment;
        });
    }
}
