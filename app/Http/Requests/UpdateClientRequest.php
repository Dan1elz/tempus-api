<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Domain\Rules\CpfValidationRule;

class UpdateClientRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:150'],
            'cpf' => [
                'required',
                'size:11',
                Rule::unique('clientes', 'cpf')->ignore($this->route('id')),
                new CpfValidationRule(),
            ],
            'data_nascimento' => ['required', 'date', 'before_or_equal:today'],
            'renda_familiar' => ['nullable', 'numeric', 'min:0'],
        ];
    }
    protected function prepareForValidation()
    {
        if ($this->has('cpf')) {
            $this->merge([
                'cpf' => preg_replace('/[^0-9]/', '', $this->input('cpf')),
            ]);
        }
    }

    public function messages()
    {
        return [
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.max' => 'O campo nome não pode ter mais de :max caracteres.',
            'cpf.required' => 'O campo CPF é obrigatório.',
            'cpf.size' => 'O campo CPF deve conter 11 dígitos numéricos.',
            'cpf.unique' => 'Este CPF já está cadastrado.',
            'data_nascimento.required' => 'O campo data de nascimento é obrigatório.',
            'data_nascimento.date' => 'O campo data de nascimento deve ser uma data válida.',
            'data_nascimento.before_or_equal' => 'A data de nascimento não pode ser uma data futura.',
            'renda_familiar.numeric' => 'O campo renda familiar deve ser um número.',
            'renda_familiar.min' => 'O campo renda familiar não pode ser negativo.',
        ];
    }
}
