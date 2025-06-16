<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreUpdateRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users,email',
        ];

        // Se for UPDATE, modifica a regra de email
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['email'] = 'required|email|max:150|unique:users,email,' . $this->route('id');
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.max' => 'O campo nome não pode ter mais de :max caracteres.',
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'O campo email deve ter um email valido',
            'email.max' => 'O campo email não pode ter mais de :max caracteres.',
            'email.unique' => 'O campo email já está cadastrado.',
        ];
    }
}
