<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
     public function wantsJson()
    {
        return true;
    }
    public function response(array $errors)
    {
        return response()->json([
            'message' => 'Validation failed',
            'errors' => $errors,
        ], 422);
    }

    public function rules(): array
    {
        return [
            'password' => [
                'required',
                'string',
                'min:8',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => 'A senha é obrigatória.',
            'password.string' => 'A senha deve ser uma string.',
            'password.min' => 'A senha deve ter no mínimo 8 caracteres.',
        ];
    }
}
