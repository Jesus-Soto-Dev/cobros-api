<?php

namespace App\Http\Requests;

use App\Enums\DebtActionType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDebtActionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::enum(DebtActionType::class)],
            'notes' => ['nullable', 'string'],
            'action_date' => ['nullable', 'date'],
        ];
    }
}
