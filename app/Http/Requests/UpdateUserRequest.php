<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('id');

        return [
            'name' => 'sometimes|string|min:2|max:255',
            'email' => [
                'sometimes',
                'email',
                'max:255',
                "unique:users,email,{$userId},id",
            ],
            'birth_date' => 'nullable|date',
        ];
    }
}
