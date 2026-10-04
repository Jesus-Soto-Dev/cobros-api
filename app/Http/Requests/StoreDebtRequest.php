<?php

namespace App\Http\Requests;

use App\Enums\DebtStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDebtRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'debtor_id' => ['required', 'integer', 'exists:debtors,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'due_date' => ['required', 'date'],
            'status' => ['nullable', Rule::enum(DebtStatus::class)],
            'notes' => ['nullable', 'string'],
        ];
    }
}
