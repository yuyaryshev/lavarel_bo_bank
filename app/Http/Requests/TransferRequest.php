<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_to_id' => 'required|uuid|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'text' => 'required|string|max:50',
        ];
    }
}
