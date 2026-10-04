<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDebtorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $debtorId = $this->route('debtor')->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'document' => ['required', 'string', 'max:20', 'unique:debtors,document,'.$debtorId],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
        ];
    }
}
